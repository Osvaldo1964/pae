-- Script para actualizar unit_cost en items basado en el último precio de compra registrado
-- Este script se ejecuta UNA SOLA VEZ para corregir datos históricos

UPDATE items i
INNER JOIN (
    SELECT 
        d.item_id,
        d.unit_price,
        m.movement_date,
        ROW_NUMBER() OVER (PARTITION BY d.item_id ORDER BY m.movement_date DESC, m.created_at DESC) as rn
    FROM inventory_movement_details d
    INNER JOIN inventory_movements m ON d.movement_id = m.id
    WHERE m.movement_type IN ('ENTRADA', 'ENTRADA_OC')
      AND d.unit_price > 0
) latest_prices ON i.id = latest_prices.item_id AND latest_prices.rn = 1
SET i.unit_cost = latest_prices.unit_price
WHERE i.unit_cost IS NULL OR i.unit_cost = 0;

-- Verificar resultados
SELECT 
    i.code,
    i.name,
    i.unit_cost as costo_actualizado,
    (SELECT SUM(inv.current_stock * i.unit_cost) FROM inventory inv WHERE inv.item_id = i.id) as valor_stock
FROM items i
WHERE i.unit_cost > 0
ORDER BY i.name;
