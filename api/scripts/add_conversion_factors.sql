-- =====================================================
-- ADICIÓN DE FACTORES DE CONVERSIÓN PARA UNIDADES
-- Permite convertir de Gramos (Receta) a KG (Compra)
-- =====================================================

ALTER TABLE measurement_units ADD COLUMN conversion_factor DECIMAL(10,4) DEFAULT 1.0000 AFTER abbreviation;

-- Actualizar factores base
-- La lógica es: Cuántas unidades "base" (gramos/mililitros/unidades) tiene la unidad de compra.
UPDATE measurement_units SET conversion_factor = 1000.0000 WHERE code = 'KG';
UPDATE measurement_units SET conversion_factor = 1.0000 WHERE code = 'G';
UPDATE measurement_units SET conversion_factor = 1000.0000 WHERE code = 'L';
UPDATE measurement_units SET conversion_factor = 1.0000 WHERE code = 'ML';
UPDATE measurement_units SET conversion_factor = 1.0000 WHERE code = 'UND';
UPDATE measurement_units SET conversion_factor = 500.0000 WHERE code = 'LB'; -- Libra colombiana estándar en retail
