<?php

namespace Controllers;

use Config\Database;
use PDO;
use Exception;

class InventoryController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    private function getPaeIdFromToken()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            try {
                $decoded = \Utils\JWT::decode($matches[1]);
                if (is_object($decoded))
                    return $decoded->data->pae_id ?? null;
                if (is_array($decoded))
                    return $decoded['data']['pae_id'] ?? null;
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    private function getUserIdFromToken()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            try {
                $decoded = \Utils\JWT::decode($matches[1]);
                if (is_object($decoded))
                    return $decoded->data->id ?? null;
                if (is_array($decoded))
                    return $decoded['data']['id'] ?? null;
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    // =====================================================
    // 1. GESTIÓN DE STOCK Y MOVIMIENTOS
    // =====================================================

    public function getStock()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $query = "SELECT 
                        i.id as item_id, i.code, i.name, 
                        fg.name as food_group, mu.abbreviation as unit,
                        COALESCE(inv.current_stock, 0) as stock,
                        inv.minimum_stock, inv.last_entry_date, inv.last_exit_date
                      FROM items i
                      JOIN food_groups fg ON i.food_group_id = fg.id
                      JOIN measurement_units mu ON i.measurement_unit_id = mu.id
                      LEFT JOIN inventory inv ON i.id = inv.item_id AND inv.pae_id = :pae_id
                      WHERE i.pae_id = :pae_id_items
                      ORDER BY fg.name, i.name";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':pae_id' => $pae_id, ':pae_id_items' => $pae_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getMovements()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $query = "SELECT m.*, u.full_name as user_name, s.name as supplier_name
                      FROM inventory_movements m
                      JOIN users u ON m.user_id = u.id
                      LEFT JOIN suppliers s ON m.supplier_id = s.id
                      WHERE m.pae_id = :pae_id
                      ORDER BY m.movement_date DESC, m.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':pae_id' => $pae_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registerMovement()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();
            $user_id = $this->getUserIdFromToken();

            $this->conn->beginTransaction();

            // 1. Cabecera
            $stmt = $this->conn->prepare("INSERT INTO inventory_movements (pae_id, user_id, supplier_id, movement_type, reference_number, movement_date, notes) 
                                         VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $pae_id,
                $user_id,
                $data['supplier_id'] ?? null,
                $data['movement_type'],
                $data['reference'] ?? null,
                $data['date'] ?? date('Y-m-d'),
                $data['notes'] ?? ''
            ]);
            $movement_id = $this->conn->lastInsertId();

            // 2. Detalles y Actualización de Stock
            $stmtDet = $this->conn->prepare("INSERT INTO inventory_movement_details (movement_id, item_id, quantity, unit_price, batch_number, expiry_date) 
                                            VALUES (?, ?, ?, ?, ?, ?)");

            foreach ($data['items'] as $item) {
                $stmtDet->execute([
                    $movement_id,
                    $item['item_id'],
                    $item['quantity'],
                    $item['unit_price'] ?? 0,
                    $item['batch'] ?? null,
                    $item['expiry'] ?? null
                ]);

                // Actualizar Stock
                $qty = ($data['movement_type'] === 'ENTRADA' || $data['movement_type'] === 'AJUSTE' && $item['quantity'] > 0)
                    ? $item['quantity'] : -$item['quantity'];

                $stmtInv = $this->conn->prepare("INSERT INTO inventory (pae_id, item_id, current_stock, last_entry_date, last_exit_date) 
                                                VALUES (?, ?, ?, ?, ?) 
                                                ON DUPLICATE KEY UPDATE 
                                                current_stock = current_stock + ?, 
                                                last_entry_date = IF(? > 0, CURRENT_DATE, last_entry_date),
                                                last_exit_date = IF(? < 0, CURRENT_DATE, last_exit_date)");

                $entry_date = ($qty > 0) ? date('Y-m-d') : null;
                $exit_date = ($qty < 0) ? date('Y-m-d') : null;
                $stmtInv->execute([$pae_id, $item['item_id'], $qty, $entry_date, $exit_date, $qty, $qty, $qty]);
            }

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Movimiento registrado y stock actualizado']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // =====================================================
    // 2. COTIZACIONES
    // =====================================================

    public function getQuotes()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $stmt = $this->conn->prepare("SELECT q.*, s.name as supplier_name FROM inventory_quotes q JOIN suppliers s ON q.supplier_id = s.id WHERE q.pae_id = ? ORDER BY q.created_at DESC");
            $stmt->execute([$pae_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getQuoteDetails($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT d.*, i.name as item_name FROM inventory_quote_details d JOIN items i ON d.item_id = i.id WHERE d.quote_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function saveQuote()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();
            $user_id = $this->getUserIdFromToken();

            $this->conn->beginTransaction();

            if (isset($data['id'])) {
                // UPDATE logic
                $stmt = $this->conn->prepare("UPDATE inventory_quotes SET supplier_id = ?, quote_number = ?, quote_date = ?, valid_until = ?, total_amount = ?, notes = ? WHERE id = ? AND pae_id = ?");
                $stmt->execute([$data['supplier_id'], $data['quote_number'], $data['quote_date'], $data['valid_until'], $data['total_amount'], $data['notes'] ?? '', $data['id'], $pae_id]);
                $quote_id = $data['id'];

                // Remove old details
                $this->conn->prepare("DELETE FROM inventory_quote_details WHERE quote_id = ?")->execute([$quote_id]);
            } else {
                // INSERT logic
                $stmt = $this->conn->prepare("INSERT INTO inventory_quotes (pae_id, user_id, supplier_id, quote_number, quote_date, valid_until, total_amount, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$pae_id, $user_id, $data['supplier_id'], $data['quote_number'], $data['quote_date'], $data['valid_until'], $data['total_amount'], $data['notes'] ?? '']);
                $quote_id = $this->conn->lastInsertId();
            }

            // Insert details
            $stmtDet = $this->conn->prepare("INSERT INTO inventory_quote_details (quote_id, item_id, quantity, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($data['items'] as $item) {
                $stmtDet->execute([$quote_id, $item['item_id'], $item['quantity'], $item['unit_price'], $item['subtotal']]);
            }

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Cotización guardada exitosamente']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteQuote($id)
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $stmt = $this->conn->prepare("DELETE FROM inventory_quotes WHERE id = ? AND pae_id = ?");
            $stmt->execute([$id, $pae_id]);
            echo json_encode(['success' => true, 'message' => 'Cotización eliminada']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // =====================================================
    // 3. ÓRDENES DE COMPRA
    // =====================================================

    public function getPurchaseOrders()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $stmt = $this->conn->prepare("SELECT po.*, s.name as supplier_name, c.name as cycle_name 
                                         FROM purchase_orders po 
                                         JOIN suppliers s ON po.supplier_id = s.id 
                                         LEFT JOIN menu_cycles c ON po.cycle_id = c.id
                                         WHERE po.pae_id = ? 
                                         ORDER BY po.created_at DESC");
            $stmt->execute([$pae_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getBranchCycleProjections($cycle_id, $branch_id)
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $query = "SELECT 
                        cp.item_id, i.name as item_name, mu.abbreviation as unit,
                        cp.total_quantity as projected_qty,
                        COALESCE((
                            SELECT SUM(ird.quantity_sent) 
                            FROM inventory_remission_details ird
                            JOIN inventory_remissions ir ON ird.remission_id = ir.id
                            WHERE ir.cycle_id = cp.cycle_id 
                              AND ir.branch_id = cp.branch_id
                              AND ird.item_id = cp.item_id 
                              AND ir.type = 'SALIDA_SEDE'
                              AND ir.status != 'CANCELADA'
                        ), 0) as delivered_qty
                      FROM cycle_projections cp
                      JOIN items i ON cp.item_id = i.id
                      JOIN measurement_units mu ON i.measurement_unit_id = mu.id
                      WHERE cp.cycle_id = ? AND cp.branch_id = ?";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([$cycle_id, $branch_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getCycleProjections($cycle_id)
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            // Obtener proyectado vs ordenado para un ciclo
            $query = "SELECT 
                        cp.item_id, i.name as item_name, mu.abbreviation as unit,
                        SUM(cp.total_quantity) as projected_qty,
                        COALESCE((
                            SELECT SUM(pod.quantity_ordered) 
                            FROM purchase_order_details pod
                            JOIN purchase_orders po ON pod.po_id = po.id
                            WHERE po.cycle_id = cp.cycle_id AND pod.item_id = cp.item_id AND po.status != 'CANCELADA'
                        ), 0) as ordered_qty
                      FROM cycle_projections cp
                      JOIN items i ON cp.item_id = i.id
                      JOIN measurement_units mu ON i.measurement_unit_id = mu.id
                      WHERE cp.cycle_id = ?
                      GROUP BY cp.item_id";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([$cycle_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPurchaseOrderDetails($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT d.*, i.name as item_name FROM purchase_order_details d JOIN items i ON d.item_id = i.id WHERE d.po_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function savePurchaseOrder()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();
            $user_id = $this->getUserIdFromToken();

            $this->conn->beginTransaction();

            if (isset($data['id'])) {
                $stmt = $this->conn->prepare("UPDATE purchase_orders SET supplier_id = ?, cycle_id = ?, po_number = ?, po_date = ?, expected_delivery = ?, total_amount = ?, notes = ?, status = ? WHERE id = ? AND pae_id = ?");
                $stmt->execute([
                    $data['supplier_id'],
                    $data['cycle_id'] ?? null,
                    $data['po_number'],
                    $data['po_date'],
                    $data['expected_delivery'] ?? null,
                    $data['total_amount'],
                    $data['notes'] ?? '',
                    $data['status'],
                    $data['id'],
                    $pae_id
                ]);
                $po_id = $data['id'];
                $this->conn->prepare("DELETE FROM purchase_order_details WHERE po_id = ?")->execute([$po_id]);
            } else {
                $stmt = $this->conn->prepare("INSERT INTO purchase_orders (pae_id, user_id, supplier_id, cycle_id, po_number, po_date, expected_delivery, total_amount, notes) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $pae_id,
                    $user_id,
                    $data['supplier_id'],
                    $data['cycle_id'] ?? null,
                    $data['po_number'],
                    $data['po_date'],
                    $data['expected_delivery'] ?? null,
                    $data['total_amount'],
                    $data['notes'] ?? ''
                ]);
                $po_id = $this->conn->lastInsertId();
            }

            $stmtDet = $this->conn->prepare("INSERT INTO purchase_order_details (po_id, item_id, quantity_ordered, unit_price, subtotal) VALUES (?, ?, ?, ?, ?)");
            foreach ($data['items'] as $item) {
                $stmtDet->execute([$po_id, $item['item_id'], $item['quantity'], $item['unit_price'], $item['subtotal']]);
            }

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Orden de Compra guardada']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    // =====================================================
    // 4. REMISIONES
    // =====================================================

    public function getRemissions()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $query = "SELECT r.*, 
                             b.name as branch_name, 
                             s.name as supplier_name, 
                             c.name as cycle_name,
                             po.po_number
                      FROM inventory_remissions r 
                      LEFT JOIN school_branches b ON r.branch_id = b.id 
                      LEFT JOIN suppliers s ON r.supplier_id = s.id
                      LEFT JOIN menu_cycles c ON r.cycle_id = c.id
                      LEFT JOIN purchase_orders po ON r.po_id = po.id
                      WHERE r.pae_id = ? 
                      ORDER BY r.created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$pae_id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getRemissionDetails($id)
    {
        try {
            $stmt = $this->conn->prepare("SELECT d.*, i.name as item_name FROM inventory_remission_details d JOIN items i ON d.item_id = i.id WHERE d.remission_id = ?");
            $stmt->execute([$id]);
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function saveRemission()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();
            $user_id = $this->getUserIdFromToken();

            $this->conn->beginTransaction();

            $type = $data['type'] ?? 'SALIDA_SEDE';

            if (isset($data['id'])) {
                $stmt = $this->conn->prepare("UPDATE inventory_remissions SET 
                    type = ?, cycle_id = ?, po_id = ?, supplier_id = ?, branch_id = ?, 
                    remission_number = ?, remission_date = ?, carrier_name = ?, 
                    vehicle_plate = ?, notes = ?, status = ? 
                    WHERE id = ? AND pae_id = ?");
                $stmt->execute([
                    $type,
                    $data['cycle_id'] ?? null,
                    $data['po_id'] ?? null,
                    $data['supplier_id'] ?? null,
                    $data['branch_id'] ?? null,
                    $data['remission_number'],
                    $data['remission_date'],
                    $data['carrier_name'] ?? '',
                    $data['vehicle_plate'] ?? '',
                    $data['notes'] ?? '',
                    $data['status'] ?? 'PENDIENTE',
                    $data['id'],
                    $pae_id
                ]);
                $rem_id = $data['id'];
                $this->conn->prepare("DELETE FROM inventory_remission_details WHERE remission_id = ?")->execute([$rem_id]);
            } else {
                $stmt = $this->conn->prepare("INSERT INTO inventory_remissions 
                    (pae_id, user_id, type, cycle_id, po_id, supplier_id, branch_id, remission_number, remission_date, carrier_name, vehicle_plate, notes) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([
                    $pae_id,
                    $user_id,
                    $type,
                    $data['cycle_id'] ?? null,
                    $data['po_id'] ?? null,
                    $data['supplier_id'] ?? null,
                    $data['branch_id'] ?? null,
                    $data['remission_number'],
                    $data['remission_date'],
                    $data['carrier_name'] ?? '',
                    $data['vehicle_plate'] ?? '',
                    $data['notes'] ?? ''
                ]);
                $rem_id = $this->conn->lastInsertId();
            }

            $stmtDet = $this->conn->prepare("INSERT INTO inventory_remission_details (remission_id, item_id, quantity_sent) VALUES (?, ?, ?)");
            foreach ($data['items'] as $item) {
                $stmtDet->execute([$rem_id, $item['item_id'], $item['quantity']]);

                // AFECTACIÓN DE STOCK (Solo si la remisión está en estado ENVIADA o similar, o siempre si es simplificado)
                // Por ahora afectamos stock directamente como en registerMovement
                $qty = ($type === 'ENTRADA_OC') ? $item['quantity'] : -$item['quantity'];

                $stmtInv = $this->conn->prepare("INSERT INTO inventory (pae_id, item_id, current_stock, last_entry_date, last_exit_date) 
                                                VALUES (?, ?, ?, ?, ?) 
                                                ON DUPLICATE KEY UPDATE 
                                                current_stock = current_stock + ?, 
                                                last_entry_date = IF(? > 0, CURRENT_DATE, last_entry_date),
                                                last_exit_date = IF(? < 0, CURRENT_DATE, last_exit_date)");

                $entry_date = ($qty > 0) ? date('Y-m-d') : null;
                $exit_date = ($qty < 0) ? date('Y-m-d') : null;
                $stmtInv->execute([$pae_id, $item['item_id'], $qty, $entry_date, $exit_date, $qty, $qty, $qty]);
            }

            $this->conn->commit();
            echo json_encode(['success' => true, 'message' => 'Remisión guardada y stock actualizado']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction())
                $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
