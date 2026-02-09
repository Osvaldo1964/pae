-- =====================================================
-- MIGRATION: Cycle-Based Cost Tracking System
-- Version: 1.3 - Fixed with correct table name (menu_cycles)
-- Date: 2026-02-09
-- =====================================================

-- 1. CREATE TABLE: item_cycle_costs (WITHOUT FOREIGN KEYS)
-- Stores average cost per item per cycle for analysis
CREATE TABLE IF NOT EXISTS item_cycle_costs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pae_id INT NOT NULL,
    item_id INT NOT NULL,
    cycle_id INT NOT NULL COMMENT 'References menu_cycles.id',
    average_cost DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    total_quantity DECIMAL(10,3) NOT NULL DEFAULT 0.000,
    total_value DECIMAL(12,2) NOT NULL DEFAULT 0.00,
    purchase_count INT DEFAULT 0,
    last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_item_cycle (pae_id, item_id, cycle_id),
    INDEX idx_pae_cycle (pae_id, cycle_id),
    INDEX idx_item (item_id),
    INDEX idx_cycle (cycle_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 2. ALTER TABLE: inventory_movements
-- Add cycle_id column if it doesn't exist
SET @column_exists = (
    SELECT COUNT(*) 
    FROM information_schema.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'inventory_movements' 
    AND COLUMN_NAME = 'cycle_id'
);

SET @sql = IF(@column_exists = 0, 
    'ALTER TABLE inventory_movements ADD COLUMN cycle_id INT NULL AFTER movement_date COMMENT ''References menu_cycles.id''',
    'SELECT "Column cycle_id already exists" as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Add index for cycle_id if not exists
SET @index_exists = (
    SELECT COUNT(*) 
    FROM information_schema.STATISTICS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'inventory_movements' 
    AND INDEX_NAME = 'idx_cycle'
);

SET @sql = IF(@index_exists = 0, 
    'ALTER TABLE inventory_movements ADD INDEX idx_cycle (cycle_id)',
    'SELECT "Index idx_cycle already exists" as message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 3. POPULATE HISTORICAL DATA
-- Associate existing movements with menu_cycles based on dates
UPDATE inventory_movements m
INNER JOIN menu_cycles c ON m.pae_id = c.pae_id 
    AND m.movement_date BETWEEN c.start_date AND c.end_date
SET m.cycle_id = c.id
WHERE m.cycle_id IS NULL;

-- 4. POPULATE item_cycle_costs FROM HISTORICAL DATA
-- Calculate average costs per item per cycle from existing movements
INSERT INTO item_cycle_costs (pae_id, item_id, cycle_id, average_cost, total_quantity, total_value, purchase_count)
SELECT 
    m.pae_id,
    d.item_id,
    m.cycle_id,
    ROUND(SUM(d.quantity * d.unit_price) / SUM(d.quantity), 2) as average_cost,
    SUM(d.quantity) as total_quantity,
    SUM(d.quantity * d.unit_price) as total_value,
    COUNT(DISTINCT m.id) as purchase_count
FROM inventory_movements m
INNER JOIN inventory_movement_details d ON m.id = d.movement_id
WHERE m.movement_type IN ('ENTRADA', 'ENTRADA_OC')
    AND m.cycle_id IS NOT NULL
    AND d.unit_price > 0
GROUP BY m.pae_id, d.item_id, m.cycle_id
ON DUPLICATE KEY UPDATE
    average_cost = VALUES(average_cost),
    total_quantity = VALUES(total_quantity),
    total_value = VALUES(total_value),
    purchase_count = VALUES(purchase_count);

-- 5. UPDATE items.unit_cost WITH WEIGHTED AVERAGE
-- Calculate global weighted average from all historical purchases
UPDATE items i
INNER JOIN (
    SELECT 
        d.item_id,
        ROUND(SUM(d.quantity * d.unit_price) / SUM(d.quantity), 2) as weighted_avg_cost
    FROM inventory_movement_details d
    INNER JOIN inventory_movements m ON d.movement_id = m.id
    WHERE m.movement_type IN ('ENTRADA', 'ENTRADA_OC')
        AND d.unit_price > 0
    GROUP BY d.item_id
) calc ON i.id = calc.item_id
SET i.unit_cost = calc.weighted_avg_cost;

-- =====================================================
-- VERIFICATION QUERIES
-- =====================================================

-- Check item_cycle_costs table
SELECT 
    'item_cycle_costs' as tabla,
    COUNT(*) as total_registros,
    COUNT(DISTINCT pae_id) as programas,
    COUNT(DISTINCT item_id) as items,
    COUNT(DISTINCT cycle_id) as ciclos
FROM item_cycle_costs;

-- Check movements with cycles
SELECT 
    'inventory_movements' as tabla,
    COUNT(*) as total_movimientos,
    COUNT(cycle_id) as con_ciclo,
    COUNT(*) - COUNT(cycle_id) as sin_ciclo
FROM inventory_movements;

-- Sample: Items with global cost vs cycle costs (first 10)
SELECT 
    i.code as codigo,
    i.name as item,
    i.unit_cost as costo_global,
    c.name as ciclo,
    icc.average_cost as costo_ciclo,
    icc.total_quantity as cant_comprada,
    icc.purchase_count as num_compras
FROM items i
LEFT JOIN item_cycle_costs icc ON i.id = icc.item_id
LEFT JOIN menu_cycles c ON icc.cycle_id = c.id
WHERE i.pae_id = (SELECT MIN(id) FROM pae_programs)
ORDER BY i.name, c.start_date
LIMIT 10;

-- Summary by cycle
SELECT 
    c.name as ciclo,
    c.start_date as inicio,
    c.end_date as fin,
    c.status as estado,
    COUNT(DISTINCT icc.item_id) as items_comprados,
    ROUND(SUM(icc.total_value), 2) as valor_total_compras,
    SUM(icc.purchase_count) as total_compras
FROM item_cycle_costs icc
JOIN menu_cycles c ON icc.cycle_id = c.id
GROUP BY c.id, c.name, c.start_date, c.end_date, c.status
ORDER BY c.start_date DESC;
