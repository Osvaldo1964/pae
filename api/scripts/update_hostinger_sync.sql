-- =====================================================
-- MIGRATION: Hostinger Sync - Daily Update
-- Date: 2026-02-18
-- Description: Consolidates Payroll tables and Cycle adjustments (Multitenancy + GENERAL category)
-- =====================================================

-- 1. ADJUST RECIPE TABLES FOR 'GENERAL' CATEGORY
-- Support for programs like 'Adulto Mayor' or others without grades
ALTER TABLE recipe_items MODIFY COLUMN age_group ENUM('PREESCOLAR','PRIMARIA_A','PRIMARIA_B','SECUNDARIA','GENERAL') NOT NULL;
ALTER TABLE recipe_nutrition MODIFY COLUMN age_group ENUM('PREESCOLAR','PRIMARIA_A','PRIMARIA_B','SECUNDARIA','GENERAL') NOT NULL;

-- 2. PAYROLL MODULE TABLES
-- Ensure all payroll infrastructure is present on Hostinger

CREATE TABLE IF NOT EXISTS `hr_payroll_config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pae_id` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `smlv` decimal(12,2) NOT NULL,
  `aux_transporte` decimal(12,2) NOT NULL,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `pae_id` (`pae_id`,`year`),
  KEY `pae_id_2` (`pae_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_payroll_periods` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pae_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `type` enum('MENSUAL','QUINCENAL') DEFAULT 'MENSUAL',
  `status` enum('ABIERTO','CERRADO') DEFAULT 'ABIERTO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pae_id` (`pae_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_payroll_concepts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pae_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('DEVENGADO','DEDUCCION') NOT NULL,
  `formula_type` enum('VALOR_FIJO','PORCENTAJE','SMLV_DEPENDIENTE') DEFAULT 'VALOR_FIJO',
  `value` decimal(12,4) DEFAULT 0.0000,
  `is_legal` tinyint(1) DEFAULT 0,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pae_id` (`pae_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_payroll_novelties` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pae_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `period_id` int(11) NOT NULL,
  `concept_id` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(155) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pae_id` (`pae_id`),
  KEY `employee_id` (`employee_id`),
  KEY `period_id` (`period_id`),
  CONSTRAINT `hr_payroll_novelties_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`),
  CONSTRAINT `hr_payroll_novelties_ibfk_2` FOREIGN KEY (`period_id`) REFERENCES `hr_payroll_periods` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_payrolls` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pae_id` int(11) NOT NULL,
  `period_id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `total_devengado` decimal(12,2) DEFAULT 0.00,
  `total_deduccion` decimal(12,2) DEFAULT 0.00,
  `total_neto` decimal(12,2) DEFAULT 0.00,
  `status` enum('PROCESADO','PAGADO','ANULADO') DEFAULT 'PROCESADO',
  `processed_at` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `pae_id` (`pae_id`),
  KEY `period_id` (`period_id`),
  KEY `employee_id` (`employee_id`),
  CONSTRAINT `hr_payrolls_ibfk_1` FOREIGN KEY (`period_id`) REFERENCES `hr_payroll_periods` (`id`),
  CONSTRAINT `hr_payrolls_ibfk_2` FOREIGN KEY (`employee_id`) REFERENCES `hr_employees` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `hr_payroll_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `payroll_id` int(11) NOT NULL,
  `concept_id` int(11) DEFAULT NULL,
  `description` varchar(150) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `payroll_id` (`payroll_id`),
  CONSTRAINT `hr_payroll_details_ibfk_1` FOREIGN KEY (`payroll_id`) REFERENCES `hr_payrolls` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. ENSURE MULTITENANCY (Indices already present in most tables)
-- Indices for pae_id are already present in menu_cycles and menus.
-- No further action needed for existing indexes.
