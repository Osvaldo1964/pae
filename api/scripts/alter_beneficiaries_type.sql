ALTER TABLE beneficiaries 
ADD COLUMN beneficiary_type ENUM('student', 'other') DEFAULT 'student' AFTER branch_id,
ADD COLUMN population_name VARCHAR(100) NULL AFTER beneficiary_type;

-- Crear índices para optimizar búsquedas por tipo
CREATE INDEX idx_beneficiarius_type ON beneficiaries(beneficiary_type);
