-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-02-2026 a las 19:15:15
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `db-pae`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `document_type_id` int(11) NOT NULL,
  `document_number` varchar(50) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `second_name` varchar(100) DEFAULT NULL,
  `last_name1` varchar(100) NOT NULL,
  `last_name2` varchar(100) DEFAULT NULL,
  `birth_date` date NOT NULL,
  `enrollment_date` date DEFAULT NULL,
  `gender` varchar(20) DEFAULT 'MASCULINO',
  `shift` varchar(20) DEFAULT 'UNICA',
  `grade` varchar(50) DEFAULT NULL,
  `group_name` varchar(50) DEFAULT NULL,
  `group_letter` varchar(10) DEFAULT NULL,
  `ethnic_group_id` int(11) NOT NULL,
  `sisben_category` varchar(10) DEFAULT NULL,
  `is_overage` tinyint(1) DEFAULT 0,
  `disability_type` varchar(50) DEFAULT 'NINGUNA',
  `is_disabled` tinyint(1) DEFAULT 0,
  `is_victim` tinyint(1) DEFAULT 0,
  `is_migrant` tinyint(1) DEFAULT 0,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `guardian_name` varchar(255) DEFAULT NULL,
  `guardian_relationship` varchar(100) DEFAULT NULL,
  `guardian_phone` varchar(50) DEFAULT NULL,
  `guardian_address` varchar(255) DEFAULT NULL,
  `guardian_email` varchar(100) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'ACTIVO',
  `modality` enum('RACION PREPARADA EN SITIO','RACION INDUSTRIALIZADA','BONO ALIMENTARIO') DEFAULT 'RACION PREPARADA EN SITIO',
  `ration_type` enum('DESAYUNO','COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE','ALMUERZO','REFRIGERIO','REFRIGERIO REFORZADO INDUSTRIALIZADO','DESAYUNO INDUSTRIALIZADO PARA EMERGENCIAS') DEFAULT 'ALMUERZO',
  `ration_type_id` int(11) DEFAULT NULL,
  `data_authorization` tinyint(1) DEFAULT 0,
  `medical_restrictions` text DEFAULT NULL,
  `observations` text DEFAULT NULL,
  `simat_id` varchar(100) DEFAULT NULL,
  `qr_uuid` varchar(100) DEFAULT NULL,
  `biometric_hash` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `beneficiaries`
--

INSERT INTO `beneficiaries` (`id`, `pae_id`, `branch_id`, `document_type_id`, `document_number`, `first_name`, `second_name`, `last_name1`, `last_name2`, `birth_date`, `enrollment_date`, `gender`, `shift`, `grade`, `group_name`, `group_letter`, `ethnic_group_id`, `sisben_category`, `is_overage`, `disability_type`, `is_disabled`, `is_victim`, `is_migrant`, `address`, `phone`, `email`, `guardian_name`, `guardian_relationship`, `guardian_phone`, `guardian_address`, `guardian_email`, `status`, `modality`, `ration_type`, `ration_type_id`, `data_authorization`, `medical_restrictions`, `observations`, `simat_id`, `qr_uuid`, `biometric_hash`, `created_at`, `updated_at`) VALUES
(31, 3, 9, 2, '1098765432', 'JUAN', 'CARLOS', 'PÉREZ', 'GONZÁLEZ', '2012-03-15', '2024-01-15', 'MASCULINO', 'MAÑANA', '6', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(32, 3, 9, 2, '1098765433', 'MARÍA', 'FERNANDA', 'RODRÍGUEZ', 'LÓPEZ', '2012-07-22', '2024-01-15', 'FEMENINO', 'MAÑANA', '6', 'A', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(33, 3, 9, 2, '1098765434', 'CARLOS', 'ANDRÉS', 'MARTÍNEZ', 'RUIZ', '2011-11-08', '2024-01-15', 'MASCULINO', 'MAÑANA', '7', 'B', NULL, 2, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(34, 3, 9, 1, '1234567890', 'ANA', 'SOFÍA', 'GARCÍA', 'HERNÁNDEZ', '2014-05-12', '2024-01-15', 'FEMENINO', 'MAÑANA', '4', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'DESAYUNO', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(35, 3, 9, 1, '1234567891', 'LUIS', 'FERNANDO', 'TORRES', 'SÁNCHEZ', '2015-09-30', '2024-01-15', 'MASCULINO', 'MAÑANA', '3', 'B', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'DESAYUNO', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(36, 3, 9, 2, '1098765435', 'CAMILA', 'ANDREA', 'DÍAZ', 'MORENO', '2013-02-18', '2024-01-15', 'FEMENINO', 'MAÑANA', '5', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(37, 3, 9, 2, '1098765436', 'DIEGO', 'ALEJANDRO', 'RAMÍREZ', 'CASTRO', '2012-12-05', '2024-01-15', 'MASCULINO', 'MAÑANA', '6', 'B', NULL, 6, 'B2', 0, 'NINGUNA', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(38, 3, 9, 1, '1234567892', 'VALENTINA', '', 'GÓMEZ', 'VARGAS', '2016-04-20', '2024-01-15', 'FEMENINO', 'MAÑANA', '2', 'A', NULL, 2, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'DESAYUNO', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(39, 3, 9, 2, '1098765437', 'SANTIAGO', 'JOSÉ', 'MENDOZA', 'ORTIZ', '2011-06-14', '2024-01-15', 'MASCULINO', 'MAÑANA', '7', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(40, 3, 9, 1, '1234567893', 'ISABELLA', 'MARÍA', 'JIMÉNEZ', 'REYES', '2015-01-25', '2024-01-15', 'FEMENINO', 'MAÑANA', '3', 'A', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'DESAYUNO', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(41, 3, 9, 2, '1098765438', 'ANDRÉS', 'FELIPE', 'CRUZ', 'NAVARRO', '2013-08-09', '2024-01-15', 'MASCULINO', 'MAÑANA', '5', 'B', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(42, 3, 9, 1, '1234567894', 'SOFÍA', 'ALEJANDRA', 'PARRA', 'SILVA', '2016-10-03', '2024-01-15', 'FEMENINO', 'MAÑANA', '2', 'B', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'DESAYUNO', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(43, 3, 9, 2, '1098765439', 'MATEO', 'DAVID', 'ROJAS', 'GUTIÉRREZ', '2012-04-28', '2024-01-15', 'MASCULINO', 'MAÑANA', '6', 'A', NULL, 2, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(44, 3, 9, 1, '1234567895', 'LUCÍA', 'VALENTINA', 'MORALES', 'PEÑA', '2017-02-14', '2024-01-15', 'FEMENINO', 'MAÑANA', '1', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'DESAYUNO', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(45, 3, 9, 2, '1098765440', 'NICOLÁS', 'EDUARDO', 'VEGA', 'RÍOS', '2011-09-19', '2024-01-15', 'MASCULINO', 'MAÑANA', '7', 'B', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 2, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 12:25:40'),
(46, 3, 10, 2, '1098765441', 'GABRIELA', 'ANDREA', 'CASTRO', 'MUÑOZ', '2012-01-10', '2024-01-15', 'FEMENINO', 'TARDE', '6', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(47, 3, 10, 2, '1098765442', 'DANIEL', 'ESTEBAN', 'HERRERA', 'LEÓN', '2011-05-22', '2024-01-15', 'MASCULINO', 'TARDE', '7', 'A', NULL, 2, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(48, 3, 10, 1, '1234567896', 'PAULA', 'CRISTINA', 'SALAZAR', 'CORTÉS', '2014-11-08', '2024-01-15', 'FEMENINO', 'TARDE', '4', 'B', NULL, 6, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(49, 3, 10, 2, '1098765443', 'SEBASTIÁN', 'CAMILO', 'AGUILAR', 'RAMOS', '2013-03-16', '2024-01-15', 'MASCULINO', 'TARDE', '5', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(50, 3, 10, 1, '1234567897', 'MARIANA', 'ISABEL', 'OSPINA', 'MEJÍA', '2015-07-29', '2024-01-15', 'FEMENINO', 'TARDE', '3', 'A', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(51, 3, 10, 2, '1098765444', 'EMILIO', 'ANTONIO', 'SUÁREZ', 'BLANCO', '2012-09-11', '2024-01-15', 'MASCULINO', 'TARDE', '6', 'B', NULL, 2, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(52, 3, 10, 1, '1234567898', 'CAROLINA', 'SOFÍA', 'MEDINA', 'ROJAS', '2016-12-04', '2024-01-15', 'FEMENINO', 'TARDE', '2', 'A', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(53, 3, 10, 2, '1098765445', 'MIGUEL', 'ÁNGEL', 'VARGAS', 'SANTOS', '2011-02-27', '2024-01-15', 'MASCULINO', 'TARDE', '7', 'B', NULL, 6, 'A2', 0, 'NINGUNA', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(54, 3, 10, 1, '1234567899', 'VALERIA', 'NICOLE', 'RÍOS', 'FERNÁNDEZ', '2017-06-18', '2024-01-15', 'FEMENINO', 'TARDE', '1', 'B', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(55, 3, 10, 2, '1098765446', 'RICARDO', 'JAVIER', 'NÚÑEZ', 'CÁRDENAS', '2013-10-05', '2024-01-15', 'MASCULINO', 'TARDE', '5', 'B', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(56, 3, 10, 1, '1234567900', 'NATALIA', 'PAOLA', 'MOLINA', 'ARANGO', '2015-04-13', '2024-01-15', 'FEMENINO', 'TARDE', '3', 'B', NULL, 2, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(57, 3, 10, 2, '1098765447', 'ALEJANDRO', 'MANUEL', 'QUINTERO', 'PALACIOS', '2012-08-21', '2024-01-15', 'MASCULINO', 'TARDE', '6', 'A', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(58, 3, 10, 1, '1234567901', 'DANIELA', 'MARCELA', 'ESCOBAR', 'GÓMEZ', '2016-01-30', '2024-01-15', 'FEMENINO', 'TARDE', '2', 'B', NULL, 6, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(59, 3, 10, 2, '1098765448', 'FELIPE', 'IGNACIO', 'LARA', 'MENDOZA', '2011-12-17', '2024-01-15', 'MASCULINO', 'TARDE', '7', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46'),
(60, 3, 10, 1, '1234567902', 'JULIANA', 'BEATRIZ', 'CANO', 'VALENCIA', '2017-05-09', '2024-01-15', 'FEMENINO', 'TARDE', '1', 'A', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE', 1, 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-09 13:05:46');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cycle_projections`
--

CREATE TABLE `cycle_projections` (
  `id` int(11) NOT NULL,
  `cycle_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `total_quantity` decimal(12,4) NOT NULL,
  `beneficiary_count` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cycle_templates`
--

CREATE TABLE `cycle_templates` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cycle_templates`
--

INSERT INTO `cycle_templates` (`id`, `pae_id`, `name`, `created_at`) VALUES
(1, 3, 'ciclo regular', '2026-02-02 02:38:13'),
(2, 3, 'PLANTILLA GENERAL', '2026-02-05 23:14:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cycle_template_days`
--

CREATE TABLE `cycle_template_days` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `meal_type` enum('DESAYUNO','COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE','ALMUERZO','REFRIGERIO','REFRIGERIO REFORZADO INDUSTRIALIZADO','DESAYUNO INDUSTRIALIZADO PARA EMERGENCIAS') NOT NULL,
  `ration_type_id` int(11) DEFAULT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cycle_template_days`
--

INSERT INTO `cycle_template_days` (`id`, `template_id`, `day_number`, `meal_type`, `ration_type_id`, `recipe_id`) VALUES
(1, 1, 1, 'DESAYUNO', 1, 1),
(2, 1, 1, 'ALMUERZO', 2, 2),
(3, 1, 2, 'DESAYUNO', 1, 4),
(4, 1, 2, 'ALMUERZO', 2, 3),
(5, 1, 3, 'DESAYUNO', 1, 1),
(6, 1, 3, 'ALMUERZO', 2, 3),
(7, 1, 4, 'DESAYUNO', 1, 1),
(8, 1, 4, 'ALMUERZO', 2, 2),
(9, 1, 5, 'DESAYUNO', 1, 1),
(10, 1, 6, 'DESAYUNO', 1, 4),
(11, 1, 7, 'DESAYUNO', 1, 4),
(12, 2, 1, 'DESAYUNO', 1, 1),
(13, 2, 1, 'ALMUERZO', 2, 3),
(14, 2, 2, 'DESAYUNO', 1, 4),
(15, 2, 2, 'ALMUERZO', 2, 2),
(16, 2, 3, 'DESAYUNO', 1, 4),
(17, 2, 3, 'ALMUERZO', 2, 3),
(18, 2, 4, 'DESAYUNO', 1, 1),
(19, 2, 4, 'ALMUERZO', 2, 2),
(20, 2, 5, 'DESAYUNO', 1, 4),
(21, 2, 5, 'ALMUERZO', 2, 3),
(22, 2, 6, 'DESAYUNO', 1, 1),
(23, 2, 6, 'ALMUERZO', 2, 2),
(24, 2, 7, 'DESAYUNO', 1, 4),
(25, 2, 7, 'ALMUERZO', 2, 3),
(26, 2, 8, 'DESAYUNO', 1, 1),
(27, 2, 8, 'ALMUERZO', 2, 2),
(28, 2, 9, 'DESAYUNO', 1, 4),
(29, 2, 9, 'ALMUERZO', 2, 3),
(30, 2, 10, 'DESAYUNO', 1, 1),
(31, 2, 10, 'ALMUERZO', 2, 2),
(32, 2, 11, 'DESAYUNO', 1, 4),
(33, 2, 11, 'ALMUERZO', 2, 3),
(34, 2, 12, 'DESAYUNO', 1, 1),
(35, 2, 12, 'ALMUERZO', 2, 2),
(36, 2, 13, 'DESAYUNO', 1, 4),
(37, 2, 13, 'ALMUERZO', 2, 3),
(38, 2, 14, 'DESAYUNO', 1, 1),
(39, 2, 14, 'ALMUERZO', 2, 2),
(40, 2, 15, 'DESAYUNO', 1, 4),
(41, 2, 15, 'ALMUERZO', 2, 3),
(42, 2, 16, 'DESAYUNO', 1, 1),
(43, 2, 16, 'ALMUERZO', 2, 2),
(44, 2, 17, 'DESAYUNO', 1, 4),
(45, 2, 17, 'ALMUERZO', 2, 3),
(46, 2, 18, 'DESAYUNO', 1, 1),
(47, 2, 18, 'ALMUERZO', 2, 2),
(48, 2, 19, 'DESAYUNO', 1, 4),
(49, 2, 19, 'ALMUERZO', 2, 3),
(50, 2, 20, 'DESAYUNO', 1, 1),
(51, 2, 20, 'ALMUERZO', 2, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `daily_consumptions`
--

CREATE TABLE `daily_consumptions` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `beneficiary_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `meal_type` enum('DESAYUNO','COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE','ALMUERZO','REFRIGERIO','REFRIGERIO REFORZADO INDUSTRIALIZADO','DESAYUNO INDUSTRIALIZADO PARA EMERGENCIAS') NOT NULL,
  `ration_type_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `synced_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_types`
--

CREATE TABLE `document_types` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `document_types`
--

INSERT INTO `document_types` (`id`, `code`, `name`) VALUES
(1, 'RC', 'REGISTRO CIVIL'),
(2, 'TI', 'TARJETA DE IDENTIDAD'),
(3, 'CC', 'CÉDULA DE CIUDADANÍA'),
(4, 'NES', 'NÚMERO ESTABLECIDO POR LA SED'),
(5, 'PEP', 'PERMISO ESPECIAL DE PERMANENCIA'),
(6, 'PPT', 'PERMISO POR PROTECCIÓN TEMPORAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ethnic_groups`
--

CREATE TABLE `ethnic_groups` (
  `id` int(11) NOT NULL,
  `code` varchar(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `ethnic_groups`
--

INSERT INTO `ethnic_groups` (`id`, `code`, `name`, `description`) VALUES
(1, '01', 'INDÍGENA', 'Propia / Concertada'),
(2, '02', 'NEGRO / AFROCOLOMBIANO', 'Estándar con enfoque cultural'),
(3, '03', 'RAIZAL', 'Regional Caribe Insular'),
(4, '04', 'PALENQUERO', 'Estándar con enfoque cultural'),
(5, '05', 'RROM (GITANO)', 'Estándar'),
(6, '06', 'SIN PERTENENCIA ÉTNICA', 'Estándar (Resolución 0003)');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `food_groups`
--

CREATE TABLE `food_groups` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `food_groups`
--

INSERT INTO `food_groups` (`id`, `code`, `name`, `description`, `color`, `created_at`) VALUES
(1, 'CEREALES', 'Cereales y Tubérculos', 'Arroz, pasta, papa, yuca, plátano, pan', '#F4A460', '2026-02-01 21:58:22'),
(2, 'PROTEICOS', 'Alimentos Proteicos', 'Carnes, pollo, pescado, huevos, leguminosas', '#DC143C', '2026-02-01 21:58:22'),
(3, 'LACTEOS', 'Lácteos', 'Leche, yogurt, queso, kumis', '#4169E1', '2026-02-01 21:58:22'),
(4, 'FRUTAS', 'Frutas', 'Frutas frescas y procesadas', '#FFD700', '2026-02-01 21:58:22'),
(5, 'VERDURAS', 'Verduras y Hortalizas', 'Vegetales frescos y procesados', '#32CD32', '2026-02-01 21:58:22'),
(6, 'GRASAS', 'Grasas y Aceites', 'Aceites vegetales, mantequilla, margarina', '#FF8C00', '2026-02-01 21:58:22'),
(7, 'AZUCARES', 'Azúcares y Dulces', 'Azúcar, panela, miel', '#FF69B4', '2026-02-01 21:58:22'),
(8, 'CONDIMENTOS', 'Condimentos y Especias', 'Sal, ajo, cebolla, especias', '#8B4513', '2026-02-01 21:58:22'),
(9, 'BEBIDAS', 'Bebidas', 'Jugos, refrescos, infusiones', '#00CED1', '2026-02-01 21:58:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hr_employees`
--

CREATE TABLE `hr_employees` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name1` varchar(100) NOT NULL,
  `last_name2` varchar(100) DEFAULT NULL,
  `document_number` varchar(20) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `termination_date` date DEFAULT NULL,
  `eps` varchar(100) DEFAULT NULL,
  `afp` varchar(100) DEFAULT NULL,
  `arl` varchar(100) DEFAULT NULL,
  `salary` decimal(12,2) DEFAULT 0.00,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `hr_employees`
--

INSERT INTO `hr_employees` (`id`, `pae_id`, `first_name`, `last_name1`, `last_name2`, `document_number`, `address`, `phone`, `email`, `position_id`, `hire_date`, `termination_date`, `eps`, `afp`, `arl`, `salary`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'MARIA CLARA', 'SUAREZ', 'RENDON', '554466', 'Calle 6C No 19-21 Campito', '3003892753', 'rayelcastrohernandez@gmail.com', 1, '2026-02-07', NULL, 'SANITAS', 'COLPENSIONES', 'SURA', 1800000.00, 'ACTIVO', '2026-02-07 13:11:14', '2026-02-07 13:11:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `hr_positions`
--

CREATE TABLE `hr_positions` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `description` varchar(100) NOT NULL,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `hr_positions`
--

INSERT INTO `hr_positions` (`id`, `pae_id`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'MANIPULADORES', 'ACTIVO', '2026-02-07 13:08:35', '2026-02-07 13:08:35');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory`
--

CREATE TABLE `inventory` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `current_stock` decimal(12,3) DEFAULT 0.000,
  `minimum_stock` decimal(12,3) DEFAULT 0.000,
  `last_entry_date` date DEFAULT NULL,
  `last_exit_date` date DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `movement_type` enum('ENTRADA','SALIDA','AJUSTE','TRASLADO') NOT NULL,
  `reference_number` varchar(50) DEFAULT NULL,
  `movement_date` date NOT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_movement_details`
--

CREATE TABLE `inventory_movement_details` (
  `id` int(11) NOT NULL,
  `movement_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(12,3) NOT NULL,
  `unit_price` decimal(10,2) DEFAULT 0.00,
  `batch_number` varchar(50) DEFAULT NULL,
  `expiry_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_quotes`
--

CREATE TABLE `inventory_quotes` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quote_number` varchar(50) DEFAULT NULL,
  `quote_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `status` enum('BORRADOR','ENVIADA','APROBADA','RECHAZADA') DEFAULT 'BORRADOR',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `inventory_quotes`
--

INSERT INTO `inventory_quotes` (`id`, `pae_id`, `user_id`, `supplier_id`, `quote_number`, `quote_date`, `valid_until`, `total_amount`, `status`, `notes`, `created_at`) VALUES
(2, 3, 1, 1, 'cot001', '2026-02-06', '2026-02-14', 18500.25, 'BORRADOR', '', '2026-02-06 16:33:08');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_quote_details`
--

CREATE TABLE `inventory_quote_details` (
  `id` int(11) NOT NULL,
  `quote_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(12,3) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `tax_percentage` decimal(5,2) DEFAULT 0.00,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_remissions`
--

CREATE TABLE `inventory_remissions` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `type` enum('ENTRADA_OC','SALIDA_SEDE') NOT NULL DEFAULT 'SALIDA_SEDE',
  `cycle_id` int(11) DEFAULT NULL,
  `po_id` int(11) DEFAULT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `remission_number` varchar(50) NOT NULL,
  `remission_date` date NOT NULL,
  `carrier_name` varchar(100) DEFAULT NULL,
  `vehicle_plate` varchar(20) DEFAULT NULL,
  `status` enum('CAMINO','ENTREGADA','CON_NOVEDAD') DEFAULT 'CAMINO',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `inventory_remission_details`
--

CREATE TABLE `inventory_remission_details` (
  `id` int(11) NOT NULL,
  `remission_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity_sent` decimal(12,3) NOT NULL,
  `quantity_received` decimal(12,3) DEFAULT 0.000,
  `novelty_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `items`
--

CREATE TABLE `items` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `food_group_id` int(11) NOT NULL,
  `measurement_unit_id` int(11) NOT NULL,
  `gross_weight` decimal(10,2) DEFAULT 100.00,
  `net_weight` decimal(10,2) DEFAULT 100.00,
  `waste_percentage` decimal(5,2) DEFAULT 0.00,
  `calories` decimal(10,2) DEFAULT 0.00,
  `proteins` decimal(10,2) DEFAULT 0.00,
  `carbohydrates` decimal(10,2) DEFAULT 0.00,
  `fats` decimal(10,2) DEFAULT 0.00,
  `fiber` decimal(10,2) DEFAULT 0.00,
  `iron` decimal(10,2) DEFAULT 0.00,
  `calcium` decimal(10,2) DEFAULT 0.00,
  `sodium` decimal(10,2) DEFAULT 0.00,
  `vitamin_a` decimal(10,2) DEFAULT 0.00,
  `vitamin_c` decimal(10,2) DEFAULT 0.00,
  `contains_gluten` tinyint(1) DEFAULT 0,
  `contains_lactose` tinyint(1) DEFAULT 0,
  `contains_peanuts` tinyint(1) DEFAULT 0,
  `contains_seafood` tinyint(1) DEFAULT 0,
  `contains_eggs` tinyint(1) DEFAULT 0,
  `contains_soy` tinyint(1) DEFAULT 0,
  `is_local_purchase` tinyint(1) DEFAULT 0,
  `local_producer` varchar(255) DEFAULT NULL,
  `sanitary_registry` varchar(100) DEFAULT NULL,
  `requires_refrigeration` tinyint(1) DEFAULT 0,
  `shelf_life_days` int(11) DEFAULT NULL,
  `unit_cost` decimal(10,2) DEFAULT 0.00,
  `last_purchase_date` date DEFAULT NULL,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_perishable` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `pae_id`, `code`, `name`, `description`, `food_group_id`, `measurement_unit_id`, `gross_weight`, `net_weight`, `waste_percentage`, `calories`, `proteins`, `carbohydrates`, `fats`, `fiber`, `iron`, `calcium`, `sodium`, `vitamin_a`, `vitamin_c`, `contains_gluten`, `contains_lactose`, `contains_peanuts`, `contains_seafood`, `contains_eggs`, `contains_soy`, `is_local_purchase`, `local_producer`, `sanitary_registry`, `requires_refrigeration`, `shelf_life_days`, `unit_cost`, `last_purchase_date`, `status`, `created_at`, `updated_at`, `is_perishable`) VALUES
(265, 3, 'ARROZ', 'ARROZ BLANCO', NULL, 1, 1, 1000.00, 1000.00, 0.00, 130.00, 2.70, 28.00, 0.30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(266, 3, 'PASTA', 'PASTA ESPAGUETI', NULL, 1, 1, 1000.00, 1000.00, 0.00, 157.00, 5.80, 30.00, 0.90, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(267, 3, 'PAPA', 'PAPA SABANERA', NULL, 1, 1, 1000.00, 850.00, 15.00, 77.00, 2.00, 17.00, 0.10, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(268, 3, 'YUCA', 'YUCA FRESCA', NULL, 1, 1, 1000.00, 750.00, 25.00, 160.00, 1.40, 38.00, 0.30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(269, 3, 'PLATANO', 'PLATANO MADURO', NULL, 1, 1, 1000.00, 650.00, 35.00, 122.00, 1.30, 32.00, 0.40, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(270, 3, 'AREPA', 'AREPA DE MAIZ', NULL, 1, 5, 1.00, 1.00, 0.00, 218.00, 4.50, 45.00, 1.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(271, 3, 'PAN', 'PAN TAJADO INTEGRAL', NULL, 1, 5, 1.00, 1.00, 0.00, 247.00, 8.50, 41.00, 3.50, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(272, 3, 'AVENA', 'AVENA EN HOJUELAS', NULL, 1, 1, 1000.00, 1000.00, 0.00, 389.00, 16.00, 66.00, 6.90, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(273, 3, 'GALLETA', 'GALLETA INTEGRAL', NULL, 1, 5, 1.00, 1.00, 0.00, 450.00, 7.00, 70.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(274, 3, 'TOSTADA', 'TOSTADA DE ARROZ', NULL, 1, 5, 1.00, 1.00, 0.00, 380.00, 8.00, 80.00, 2.50, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(275, 3, 'POLLO', 'PECHUGA DE POLLO', NULL, 2, 1, 1000.00, 850.00, 15.00, 165.00, 31.00, 0.00, 3.60, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(276, 3, 'CARNE', 'CARNE DE RES (MURILLO)', NULL, 2, 1, 1000.00, 950.00, 5.00, 250.00, 26.00, 0.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(277, 3, 'CERDO', 'LOMO DE CERDO', NULL, 2, 1, 1000.00, 950.00, 5.00, 242.00, 27.00, 0.00, 14.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(278, 3, 'HUEVO', 'HUEVO AA FRESCO', NULL, 2, 5, 60.00, 50.00, 16.00, 155.00, 13.00, 1.10, 11.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(279, 3, 'FRIJOL', 'FRIJOL CARGAMANTO', NULL, 2, 1, 1000.00, 1000.00, 0.00, 333.00, 23.00, 60.00, 1.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(280, 3, 'LENTEJA', 'LENTEJA SECA', NULL, 2, 1, 1000.00, 1000.00, 0.00, 352.00, 25.00, 63.00, 1.10, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(281, 3, 'GARBANZO', 'GARBANZO SECO', NULL, 2, 1, 1000.00, 1000.00, 0.00, 364.00, 19.00, 61.00, 6.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(282, 3, 'PESCADO', 'FILETE DE TILAPIA', NULL, 2, 1, 1000.00, 900.00, 10.00, 96.00, 20.00, 0.00, 1.70, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(283, 3, 'ATUN', 'ATUN EN AGUA (LATA)', NULL, 2, 1, 170.00, 120.00, 30.00, 116.00, 25.00, 0.00, 0.80, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(284, 3, 'ARVEJA', 'ARVEJA SECA', NULL, 2, 1, 1000.00, 1000.00, 0.00, 341.00, 24.00, 60.00, 1.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(285, 3, 'BLANQUILLO', 'FRIJOL BLANQUILLO', NULL, 2, 1, 1000.00, 1000.00, 0.00, 337.00, 23.00, 60.00, 1.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(286, 3, 'QUESO', 'QUESO CAMPESINO', NULL, 3, 1, 1000.00, 1000.00, 0.00, 264.00, 18.00, 3.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(287, 3, 'LECHE', 'LECHE ENTERA PASTEURIZADA', NULL, 3, 3, 100.00, 100.00, 0.00, 61.00, 3.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(288, 3, 'YOGURT', 'YOGURT NATURAL', NULL, 3, 3, 100.00, 100.00, 0.00, 59.00, 3.50, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(289, 3, 'KUMIS', 'KUMIS FRESCO', NULL, 3, 3, 100.00, 100.00, 0.00, 78.00, 3.30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(290, 3, 'AVENALAC', 'AVENA CON LECHE PREPARADA', NULL, 3, 3, 100.00, 100.00, 0.00, 120.00, 4.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(291, 3, 'MATANTE', 'BIENESTARINA MASAMORRA', NULL, 3, 3, 100.00, 100.00, 0.00, 110.00, 5.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(292, 3, 'ZANAHORIA', 'ZANAHORIA FRESCA', NULL, 5, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(293, 3, 'TOMATE', 'TOMATE CHONTO', NULL, 5, 1, 100.00, 100.00, 5.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(294, 3, 'CEBOLLA', 'CEBOLLA CABEZONA', NULL, 5, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(295, 3, 'HABICHUELA', 'HABICHUELA VERDE', NULL, 5, 1, 100.00, 100.00, 8.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(296, 3, 'REPOLLO', 'REPOLLO BLANCO', NULL, 5, 1, 100.00, 100.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(297, 3, 'TOMATEA', 'TOMATE DE ARBOL', NULL, 4, 1, 100.00, 100.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(298, 3, 'GUAYABA', 'GUAYABA PERA', NULL, 4, 1, 100.00, 100.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(299, 3, 'NARANJA', 'NARANJA TANGERINA', NULL, 4, 1, 100.00, 100.00, 40.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(300, 3, 'MANZANA', 'MANZANA ROJA', NULL, 4, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(301, 3, 'BANANO', 'BANANO URABA', '', 4, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 0, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-09 12:44:05', 1),
(302, 3, 'PAPAYA', 'PAPAYA MELON', NULL, 4, 1, 100.00, 100.00, 25.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(303, 3, 'AHUYAMA', 'AHUYAMA BOLO', NULL, 5, 1, 100.00, 100.00, 25.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(304, 3, 'ESPINACA', 'ESPINACA BOGOTANA', NULL, 5, 1, 100.00, 100.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(305, 3, 'LECHUGA', 'LECHUGA BATAVIA', NULL, 5, 1, 100.00, 100.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(306, 3, 'PEPINO', 'PEPINO COHOMBRO', NULL, 5, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(307, 3, 'PI├æA', 'PI├æA ORO MIEL', NULL, 4, 1, 100.00, 100.00, 45.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(308, 3, 'MORA', 'MORA DE CASTILLA', NULL, 4, 1, 100.00, 100.00, 5.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(309, 3, 'LIMON', 'LIMON TAHITI', NULL, 4, 1, 100.00, 100.00, 50.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(310, 3, 'ACEITE', 'ACEITE VEGETAL DE GIRASOL', NULL, 6, 3, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(311, 3, 'SAL', 'SAL REFINADA YODADA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(312, 3, 'AZUCAR', 'AZUCAR BLANCA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(313, 3, 'PANELA', 'PANELA REDONDA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(314, 3, 'AJO', 'AJO EN PASTA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58', 0),
(315, 3, 'GUINEO', 'GUINEO', '', 1, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, 0, 0.00, NULL, 'ACTIVO', '2026-02-09 12:44:32', '2026-02-09 12:44:51', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `item_cycle_costs`
--

CREATE TABLE `item_cycle_costs` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `cycle_id` int(11) NOT NULL,
  `average_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_quantity` decimal(10,3) NOT NULL DEFAULT 0.000,
  `total_value` decimal(12,2) NOT NULL DEFAULT 0.00,
  `purchase_count` int(11) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `measurement_units`
--

CREATE TABLE `measurement_units` (
  `id` int(11) NOT NULL,
  `code` varchar(20) NOT NULL,
  `name` varchar(50) NOT NULL,
  `abbreviation` varchar(10) NOT NULL,
  `type` enum('PESO','VOLUMEN','UNIDAD') DEFAULT 'PESO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `measurement_units`
--

INSERT INTO `measurement_units` (`id`, `code`, `name`, `abbreviation`, `type`, `created_at`) VALUES
(1, 'KG', 'Kilogramos', 'kg', 'PESO', '2026-02-01 21:58:22'),
(2, 'G', 'Gramos', 'g', 'PESO', '2026-02-01 21:58:22'),
(3, 'L', 'Litros', 'L', 'VOLUMEN', '2026-02-01 21:58:22'),
(4, 'ML', 'Mililitros', 'ml', 'VOLUMEN', '2026-02-01 21:58:22'),
(5, 'UND', 'Unidades', 'und', 'UNIDAD', '2026-02-01 21:58:22'),
(6, 'LB', 'Libras', 'lb', 'PESO', '2026-02-01 21:58:22');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `day_number` int(11) DEFAULT NULL,
  `meal_type` enum('DESAYUNO','COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE','ALMUERZO','REFRIGERIO','REFRIGERIO REFORZADO INDUSTRIALIZADO','DESAYUNO INDUSTRIALIZADO PARA EMERGENCIAS') DEFAULT 'ALMUERZO',
  `ration_type_id` int(11) DEFAULT NULL,
  `age_group` enum('PREESCOLAR','PRIMARIA_A','PRIMARIA_B','SECUNDARIA','TODOS') DEFAULT 'TODOS',
  `has_dairy` tinyint(1) DEFAULT 0,
  `has_protein` tinyint(1) DEFAULT 0,
  `has_cereal` tinyint(1) DEFAULT 0,
  `has_fruit` tinyint(1) DEFAULT 0,
  `has_vegetable` tinyint(1) DEFAULT 0,
  `total_calories` decimal(10,2) DEFAULT 0.00,
  `total_proteins` decimal(10,2) DEFAULT 0.00,
  `total_carbohydrates` decimal(10,2) DEFAULT 0.00,
  `total_fats` decimal(10,2) DEFAULT 0.00,
  `total_iron` decimal(10,2) DEFAULT 0.00,
  `total_calcium` decimal(10,2) DEFAULT 0.00,
  `total_cost` decimal(10,2) DEFAULT 0.00,
  `preparation_instructions` text DEFAULT NULL,
  `allergen_warnings` text DEFAULT NULL,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menus`
--

INSERT INTO `menus` (`id`, `pae_id`, `cycle_id`, `name`, `day_number`, `meal_type`, `ration_type_id`, `age_group`, `has_dairy`, `has_protein`, `has_cereal`, `has_fruit`, `has_vegetable`, `total_calories`, `total_proteins`, `total_carbohydrates`, `total_fats`, `total_iron`, `total_calcium`, `total_cost`, `preparation_instructions`, `allergen_warnings`, `status`, `created_at`, `updated_at`) VALUES
(241, 3, NULL, 'Día 1 - 2026-02-02', 1, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(242, 3, NULL, 'Día 2 - 2026-02-03', 2, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(243, 3, NULL, 'Día 3 - 2026-02-04', 3, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(244, 3, NULL, 'Día 4 - 2026-02-05', 4, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(245, 3, NULL, 'Día 5 - 2026-02-06', 5, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(246, 3, NULL, 'Día 6 - 2026-02-09', 6, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(247, 3, NULL, 'Día 7 - 2026-02-10', 7, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(248, 3, NULL, 'Día 8 - 2026-02-11', 8, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(249, 3, NULL, 'Día 9 - 2026-02-12', 9, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(250, 3, NULL, 'Día 10 - 2026-02-13', 10, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(251, 3, NULL, 'Día 11 - 2026-02-16', 11, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(252, 3, NULL, 'Día 12 - 2026-02-17', 12, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(253, 3, NULL, 'Día 13 - 2026-02-18', 13, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(254, 3, NULL, 'Día 14 - 2026-02-19', 14, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(255, 3, NULL, 'Día 15 - 2026-02-20', 15, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(256, 3, NULL, 'Día 16 - 2026-02-23', 16, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(257, 3, NULL, 'Día 17 - 2026-02-24', 17, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(258, 3, NULL, 'Día 18 - 2026-02-25', 18, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(259, 3, NULL, 'Día 19 - 2026-02-26', 19, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(260, 3, NULL, 'Día 20 - 2026-02-27', 20, 'ALMUERZO', 2, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-06 00:19:44', '2026-02-09 12:25:41'),
(286, 3, NULL, 'Día 1 - 2026-03-02', 1, 'ALMUERZO', NULL, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-09 13:08:44', '2026-02-09 13:08:44'),
(287, 3, NULL, 'Día 2 - 2026-03-03', 2, 'ALMUERZO', NULL, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-09 13:08:44', '2026-02-09 13:08:44'),
(288, 3, NULL, 'Día 3 - 2026-03-04', 3, 'ALMUERZO', NULL, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-09 13:08:44', '2026-02-09 13:08:44'),
(289, 3, NULL, 'Día 4 - 2026-03-05', 4, 'ALMUERZO', NULL, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-09 13:08:44', '2026-02-09 13:08:44'),
(290, 3, NULL, 'Día 5 - 2026-03-06', 5, 'ALMUERZO', NULL, 'TODOS', 0, 0, 0, 0, 0, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, NULL, NULL, 'ACTIVO', '2026-02-09 13:08:44', '2026-02-09 13:08:44');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_cycles`
--

CREATE TABLE `menu_cycles` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` int(11) DEFAULT 20,
  `is_validated` tinyint(1) DEFAULT 0,
  `validated_by` int(11) DEFAULT NULL,
  `validated_at` timestamp NULL DEFAULT NULL,
  `status` enum('BORRADOR','ACTIVO','FINALIZADO') DEFAULT 'BORRADOR',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `standard_quantity` decimal(10,3) NOT NULL,
  `gross_quantity` decimal(10,3) DEFAULT NULL,
  `preparation_method` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `menu_recipes`
--

CREATE TABLE `menu_recipes` (
  `id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `meal_type` enum('DESAYUNO','COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE','ALMUERZO','REFRIGERIO','REFRIGERIO REFORZADO INDUSTRIALIZADO','DESAYUNO INDUSTRIALIZADO PARA EMERGENCIAS') NOT NULL,
  `ration_type_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `menu_recipes`
--

INSERT INTO `menu_recipes` (`id`, `menu_id`, `recipe_id`, `meal_type`, `ration_type_id`) VALUES
(41, 241, 1, 'DESAYUNO', 1),
(42, 241, 3, 'ALMUERZO', 2),
(43, 242, 4, 'DESAYUNO', 1),
(44, 242, 2, 'ALMUERZO', 2),
(45, 243, 3, 'ALMUERZO', 2),
(46, 243, 4, 'DESAYUNO', 1),
(47, 244, 1, 'DESAYUNO', 1),
(48, 244, 2, 'ALMUERZO', 2),
(49, 245, 4, 'DESAYUNO', 1),
(50, 245, 3, 'ALMUERZO', 2),
(51, 246, 2, 'ALMUERZO', 2),
(52, 246, 1, 'DESAYUNO', 1),
(53, 247, 4, 'DESAYUNO', 1),
(54, 247, 3, 'ALMUERZO', 2),
(55, 248, 1, 'DESAYUNO', 1),
(56, 248, 2, 'ALMUERZO', 2),
(57, 249, 3, 'ALMUERZO', 2),
(58, 249, 4, 'DESAYUNO', 1),
(59, 250, 1, 'DESAYUNO', 1),
(60, 250, 2, 'ALMUERZO', 2),
(61, 251, 3, 'ALMUERZO', 2),
(62, 251, 4, 'DESAYUNO', 1),
(63, 252, 1, 'DESAYUNO', 1),
(64, 252, 2, 'ALMUERZO', 2),
(65, 253, 4, 'DESAYUNO', 1),
(66, 253, 3, 'ALMUERZO', 2),
(67, 254, 2, 'ALMUERZO', 2),
(68, 254, 1, 'DESAYUNO', 1),
(69, 255, 4, 'DESAYUNO', 1),
(70, 255, 3, 'ALMUERZO', 2),
(71, 256, 2, 'ALMUERZO', 2),
(72, 256, 1, 'DESAYUNO', 1),
(73, 257, 4, 'DESAYUNO', 1),
(74, 257, 3, 'ALMUERZO', 2),
(75, 258, 1, 'DESAYUNO', 1),
(76, 258, 2, 'ALMUERZO', 2),
(77, 259, 4, 'DESAYUNO', 1),
(78, 259, 3, 'ALMUERZO', 2),
(79, 260, 1, 'DESAYUNO', 1),
(80, 260, 2, 'ALMUERZO', 2),
(122, 286, 1, 'DESAYUNO', 1),
(123, 286, 2, 'ALMUERZO', 2),
(124, 287, 4, 'DESAYUNO', 1),
(125, 287, 3, 'ALMUERZO', 2),
(126, 288, 1, 'DESAYUNO', 1),
(127, 288, 3, 'ALMUERZO', 2),
(128, 289, 1, 'DESAYUNO', 1),
(129, 289, 2, 'ALMUERZO', 2),
(130, 290, 1, 'DESAYUNO', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `route_key` varchar(50) NOT NULL COMMENT 'Identificador para el router JS',
  `icon` varchar(50) DEFAULT 'fas fa-circle',
  `order_index` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `group_id`, `name`, `description`, `route_key`, `icon`, `order_index`) VALUES
(1, 1, 'Usuarios', 'Gestión de usuarios y accesos', 'users', 'fas fa-user-cog', 0),
(2, 1, 'Roles y Permisos', 'Configuración de perfiles de acceso', 'roles', 'fas fa-shield-alt', 0),
(3, 2, 'Sedes Educativas', 'Gestión de instituciones y sedes', 'sedes', 'fas fa-school', 0),
(4, 6, 'Proveedores', 'Directorio de proveedores', 'proveedores', 'fas fa-truck', 1),
(5, 3, 'Estudiantes', 'Base de datos de titulares de derecho', 'beneficiarios', 'fas fa-child', 0),
(6, 3, 'Novedades', 'Reporte de ausentismos y retiros', 'novedades', 'fas fa-exclamation-circle', 0),
(9, 5, 'Dashboards', 'Tableros de control gerencial', 'dashboard_kpi', 'fas fa-tachometer-alt', 0),
(16, 4, 'Ciclos y Minutas', 'Gesti¾n de plantillas y ciclos de men·s', 'minutas', 'fas fa-calendar-alt', 0),
(18, 4, 'Ítems', 'Gestión de insumos e ingredientes', 'items', 'fas fa-apple-alt', 0),
(19, 4, 'Recetario', 'Maestro de recetas y platos base', 'recetario', 'fas fa-book-medical', 0),
(20, 6, 'Salidas de Almacén', 'Entregas a instituciones educativas (Sedes)', 'salidas', 'fas fa-truck-loading', 5),
(21, 6, 'Almacén', 'Control de stock e inventario', 'almacen', 'fas fa-warehouse', 6),
(22, 6, 'Cotizaciones', 'Gestión de precios de proveedores', 'cotizaciones', 'fas fa-file-invoice-dollar', 2),
(23, 6, 'Órdenes de Compra', 'Gestión de pedidos a proveedores', 'compras', 'fas fa-shopping-cart', 3),
(24, 6, 'Remisiones (Entradas)', 'Ingreso de mercancÝa desde proveedores (OC)', 'remisiones', 'fas fa-file-import', 4),
(25, 3, 'Reporte de Asistencia (QR)', 'Registro de entregas capturadas por escáner', 'consumos', 'fas fa-id-card-alt', 10),
(26, 7, 'Cargos', 'Gestión de cargos y perfiles', 'hr-positions', 'fas fa-briefcase', 1),
(27, 7, 'Empleados', 'Maestro de empleados y nómina', 'hr-employees', 'fas fa-user-tie', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `module_groups`
--

CREATE TABLE `module_groups` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `icon` varchar(50) DEFAULT 'fas fa-folder',
  `order_index` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `module_groups`
--

INSERT INTO `module_groups` (`id`, `name`, `icon`, `order_index`) VALUES
(1, 'Configuración', 'fas fa-cogs', 1),
(2, 'Instituciones', 'fas fa-map-marked-alt', 2),
(3, 'Beneficiarios', 'fas fa-users', 3),
(4, 'Cocina', 'fas fa-utensils', 4),
(5, 'Reportes', 'fas fa-chart-line', 7),
(6, 'Inventarios', 'fas fa-boxes', 5),
(7, 'Recurso Humano', 'fas fa-id-card', 6);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `module_permissions`
--

CREATE TABLE `module_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `pae_id` int(11) DEFAULT NULL,
  `module_id` int(11) NOT NULL,
  `can_create` tinyint(1) DEFAULT 0,
  `can_read` tinyint(1) DEFAULT 0,
  `can_update` tinyint(1) DEFAULT 0,
  `can_delete` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `module_permissions`
--

INSERT INTO `module_permissions` (`id`, `role_id`, `pae_id`, `module_id`, `can_create`, `can_read`, `can_update`, `can_delete`) VALUES
(1, 1, NULL, 1, 1, 1, 1, 1),
(2, 1, NULL, 2, 1, 1, 1, 1),
(3, 1, NULL, 3, 1, 1, 1, 1),
(4, 1, NULL, 4, 1, 1, 1, 1),
(5, 1, NULL, 5, 1, 1, 1, 1),
(6, 1, NULL, 6, 1, 1, 1, 1),
(9, 1, NULL, 9, 1, 1, 1, 1),
(16, 4, 3, 2, 0, 0, 0, 0),
(17, 4, 3, 1, 0, 0, 0, 0),
(18, 4, 3, 3, 0, 1, 1, 0),
(20, 4, 3, 5, 0, 1, 0, 0),
(22, 4, 3, 4, 0, 0, 0, 0),
(23, 4, 3, 6, 0, 1, 0, 0),
(24, 4, 3, 9, 0, 1, 0, 0),
(25, 1, 3, 4, 1, 1, 1, 1),
(26, 2, 3, 1, 0, 0, 0, 0),
(27, 2, 3, 3, 0, 0, 0, 0),
(28, 2, 3, 6, 0, 0, 0, 0),
(29, 2, 3, 4, 0, 0, 0, 0),
(30, 2, 3, 5, 0, 0, 0, 0),
(31, 2, 3, 2, 0, 0, 0, 0),
(34, 2, 3, 9, 0, 0, 0, 0),
(38, 1, 3, 18, 1, 1, 1, 1),
(39, 1, 3, 19, 1, 1, 1, 1),
(40, 1, 3, 20, 1, 1, 1, 1),
(41, 6, 3, 18, 1, 1, 1, 1),
(42, 6, 3, 19, 1, 1, 1, 1),
(43, 6, 3, 20, 1, 1, 1, 1),
(44, 1, 3, 22, 1, 1, 1, 1),
(45, 1, 3, 23, 1, 1, 1, 1),
(46, 1, 3, 24, 1, 1, 1, 1),
(47, 6, 3, 22, 1, 1, 1, 1),
(48, 6, 3, 23, 1, 1, 1, 1),
(49, 6, 3, 24, 1, 1, 1, 1),
(50, 1, 3, 21, 1, 1, 1, 1),
(51, 6, 3, 21, 1, 1, 1, 1),
(52, 1, 3, 1, 0, 0, 0, 0),
(53, 1, 3, 6, 0, 0, 0, 0),
(54, 1, 3, 2, 0, 0, 0, 0),
(55, 1, 3, 5, 0, 0, 0, 0),
(56, 1, 3, 3, 0, 0, 0, 0),
(57, 1, 3, 9, 1, 1, 1, 1),
(60, 1, 3, 16, 1, 1, 1, 1),
(61, 6, 3, 16, 1, 1, 1, 1),
(62, 2, NULL, 25, 1, 1, 1, 1),
(63, 5, NULL, 25, 1, 1, 1, 1),
(64, 3, NULL, 25, 1, 1, 1, 1),
(65, 6, NULL, 25, 1, 1, 1, 1),
(66, 4, NULL, 25, 1, 1, 1, 1),
(67, 1, NULL, 25, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nutritional_parameters`
--

CREATE TABLE `nutritional_parameters` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) DEFAULT NULL,
  `age_group` enum('PREESCOLAR','PRIMARIA_A','PRIMARIA_B','SECUNDARIA') NOT NULL,
  `meal_type` enum('DESAYUNO','COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE','ALMUERZO','REFRIGERIO','REFRIGERIO REFORZADO INDUSTRIALIZADO','DESAYUNO INDUSTRIALIZADO PARA EMERGENCIAS') NOT NULL,
  `ration_type_id` int(11) DEFAULT NULL,
  `min_calories` decimal(10,2) NOT NULL,
  `max_calories` decimal(10,2) NOT NULL,
  `min_proteins` decimal(10,2) NOT NULL,
  `max_proteins` decimal(10,2) NOT NULL,
  `min_iron` decimal(10,2) NOT NULL,
  `min_calcium` decimal(10,2) NOT NULL,
  `max_sodium` decimal(10,2) DEFAULT NULL,
  `max_egg_frequency` int(11) DEFAULT 2,
  `max_fried_frequency` int(11) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `nutritional_parameters`
--

INSERT INTO `nutritional_parameters` (`id`, `pae_id`, `age_group`, `meal_type`, `ration_type_id`, `min_calories`, `max_calories`, `min_proteins`, `max_proteins`, `min_iron`, `min_calcium`, `max_sodium`, `max_egg_frequency`, `max_fried_frequency`, `created_at`, `updated_at`) VALUES
(1, 3, 'PREESCOLAR', 'ALMUERZO', 2, 300.00, 400.00, 10.00, 15.00, 2.50, 150.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-09 12:25:41'),
(2, 3, 'PRIMARIA_A', 'ALMUERZO', 2, 450.00, 550.00, 15.00, 20.00, 3.50, 200.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-09 12:25:41'),
(3, 3, 'SECUNDARIA', 'ALMUERZO', 2, 550.00, 700.00, 20.00, 30.00, 4.50, 250.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-09 12:25:41'),
(4, 3, 'PREESCOLAR', 'REFRIGERIO', 3, 150.00, 200.00, 5.00, 8.00, 1.00, 100.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-09 12:25:41'),
(5, 3, 'PRIMARIA_A', 'REFRIGERIO', 3, 200.00, 250.00, 8.00, 12.00, 1.50, 150.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-09 12:25:41'),
(6, 3, 'SECUNDARIA', 'REFRIGERIO', 3, 250.00, 300.00, 10.00, 15.00, 2.00, 200.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-09 12:25:41'),
(7, 3, 'PRIMARIA_B', 'ALMUERZO', 2, 500.00, 600.00, 18.00, 25.00, 4.00, 220.00, NULL, 2, 1, '2026-02-03 23:29:58', '2026-02-09 12:25:41'),
(8, 3, 'PRIMARIA_B', 'REFRIGERIO', 3, 220.00, 270.00, 9.00, 13.00, 1.80, 170.00, NULL, 2, 1, '2026-02-03 23:29:58', '2026-02-09 12:25:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pae_programs`
--

CREATE TABLE `pae_programs` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Programa (PAE Magdalena)',
  `operator_name` varchar(100) DEFAULT NULL COMMENT 'Empresa que opera',
  `operator_nit` varchar(20) DEFAULT NULL,
  `operator_address` varchar(200) DEFAULT NULL,
  `operator_phone` varchar(20) DEFAULT NULL,
  `operator_email` varchar(100) DEFAULT NULL,
  `entity_name` varchar(100) DEFAULT NULL COMMENT 'Entidad Territorial',
  `nit` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `department` varchar(50) DEFAULT NULL,
  `entity_logo_path` varchar(255) DEFAULT NULL,
  `operator_logo_path` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL COMMENT 'Logo Principal (Usualmente el de la Entidad o compuesto)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pae_programs`
--

INSERT INTO `pae_programs` (`id`, `name`, `operator_name`, `operator_nit`, `operator_address`, `operator_phone`, `operator_email`, `entity_name`, `nit`, `email`, `city`, `department`, `entity_logo_path`, `operator_logo_path`, `logo_path`, `created_at`) VALUES
(3, 'PAE SANTA MARTA', 'OPERADOR DE PRUEBA', '90090900-1', 'Calle 6C No 19-21 Campito', '3003892753', 'admin@pae.gov.co', 'ALCALDIA DE SANTA MARTA', '95655', 'paestamta@mail.com', 'SANTA MARTA', 'MAGDALENA', 'assets/img/logos/entity_1769954711.jpg', 'assets/img/logos/operator_1769954711.jpeg', NULL, '2026-02-01 13:50:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pae_ration_types`
--

CREATE TABLE `pae_ration_types` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pae_ration_types`
--

INSERT INTO `pae_ration_types` (`id`, `pae_id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'DESAYUNO', NULL, 'ACTIVO', '2026-02-09 12:25:40', '2026-02-09 12:25:40'),
(2, 3, 'ALMUERZO', NULL, 'ACTIVO', '2026-02-09 12:25:40', '2026-02-09 12:25:40'),
(3, 3, 'REFRIGERIO', NULL, 'ACTIVO', '2026-02-09 12:25:40', '2026-02-09 12:25:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `cycle_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `quote_id` int(11) DEFAULT NULL,
  `po_number` varchar(50) NOT NULL,
  `po_date` date NOT NULL,
  `expected_delivery` date DEFAULT NULL,
  `total_amount` decimal(12,2) DEFAULT 0.00,
  `status` enum('PENDIENTE','RECIBIDA_PARCIAL','RECIBIDA_TOTAL','CANCELADA') DEFAULT 'PENDIENTE',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `purchase_order_details`
--

CREATE TABLE `purchase_order_details` (
  `id` int(11) NOT NULL,
  `po_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity_ordered` decimal(12,3) NOT NULL,
  `quantity_received` decimal(12,3) DEFAULT 0.000,
  `unit_price` decimal(10,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `meal_type` enum('DESAYUNO','COMPLEMENTO ALIMENTARIO JORNADA DE LA TARDE','ALMUERZO','REFRIGERIO','REFRIGERIO REFORZADO INDUSTRIALIZADO','DESAYUNO INDUSTRIALIZADO PARA EMERGENCIAS') NOT NULL,
  `ration_type_id` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `total_calories` decimal(10,2) DEFAULT 0.00,
  `total_proteins` decimal(10,2) DEFAULT 0.00,
  `total_carbohydrates` decimal(10,2) DEFAULT 0.00,
  `total_fats` decimal(10,2) DEFAULT 0.00,
  `status` enum('ACTIVO','INACTIVO') DEFAULT 'ACTIVO',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recipes`
--

INSERT INTO `recipes` (`id`, `pae_id`, `name`, `meal_type`, `ration_type_id`, `description`, `total_calories`, `total_proteins`, `total_carbohydrates`, `total_fats`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'HUEVOS REVUELTOS CON TOMATE Y CEBOLLA', 'DESAYUNO', 1, 'HUEVOS REVUELTOS CON TOMATE Y CEBOLLA - CAFE CON LECHE - FRUTA', 2.79, 0.23, 0.02, 0.20, 'ACTIVO', '2026-02-01 23:27:08', '2026-02-09 12:25:40'),
(2, 3, 'Arroz con Pollo y Ensalada', 'ALMUERZO', 2, 'ARROZ CON POLLO - ENSALADA - JUGO DE NARANJA', 0.72, 0.05, 0.11, 0.01, 'ACTIVO', '2026-02-02 01:00:26', '2026-02-09 12:25:40'),
(3, 3, 'SOPA DE POLLO', 'ALMUERZO', 2, 'SOPA DE POLLO', 0.33, 0.06, 0.00, 0.01, 'ACTIVO', '2026-02-02 01:06:00', '2026-02-09 12:25:40'),
(4, 3, 'MOTE DE GUINEO VERDE', 'DESAYUNO', 1, 'MOTE DE GUINEO - MANZANA - CAFE CON LECHE', 0.93, 0.20, 0.00, 0.01, 'ACTIVO', '2026-02-02 01:07:36', '2026-02-09 12:25:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recipe_items`
--

CREATE TABLE `recipe_items` (
  `id` int(11) NOT NULL,
  `recipe_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `age_group` enum('PREESCOLAR','PRIMARIA_A','PRIMARIA_B','SECUNDARIA') NOT NULL,
  `quantity` decimal(10,3) NOT NULL,
  `preparation_method` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recipe_items`
--

INSERT INTO `recipe_items` (`id`, `recipe_id`, `item_id`, `age_group`, `quantity`, `preparation_method`) VALUES
(70, 2, 265, 'PREESCOLAR', 0.030, 'Caliente'),
(71, 2, 265, 'PRIMARIA_A', 0.030, 'Caliente'),
(72, 2, 265, 'PRIMARIA_B', 0.030, 'Caliente'),
(73, 2, 265, 'SECUNDARIA', 0.400, 'Caliente'),
(74, 2, 275, 'PREESCOLAR', 0.100, 'Caliente'),
(75, 2, 275, 'PRIMARIA_A', 0.100, 'Caliente'),
(76, 2, 275, 'PRIMARIA_B', 0.100, 'Caliente'),
(77, 2, 275, 'SECUNDARIA', 0.120, 'Caliente'),
(78, 1, 278, 'PREESCOLAR', 1.500, 'Caliente'),
(79, 1, 278, 'PRIMARIA_A', 1.500, 'Caliente'),
(80, 1, 278, 'PRIMARIA_B', 1.500, 'Caliente'),
(81, 1, 278, 'SECUNDARIA', 1.800, 'Caliente'),
(82, 4, 283, 'PREESCOLAR', 0.500, 'Caliente'),
(83, 4, 283, 'PRIMARIA_A', 0.600, 'Caliente'),
(84, 4, 283, 'PRIMARIA_B', 0.700, 'Caliente'),
(85, 4, 283, 'SECUNDARIA', 0.800, 'Caliente'),
(86, 3, 275, 'PREESCOLAR', 0.130, 'Caliente'),
(87, 3, 275, 'PRIMARIA_A', 0.140, 'Caliente'),
(88, 3, 275, 'PRIMARIA_B', 0.150, 'Caliente'),
(89, 3, 275, 'SECUNDARIA', 0.200, 'Caliente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recipe_nutrition`
--

CREATE TABLE `recipe_nutrition` (
  `recipe_id` int(11) NOT NULL,
  `age_group` enum('PREESCOLAR','PRIMARIA_A','PRIMARIA_B','SECUNDARIA') NOT NULL,
  `total_calories` decimal(10,2) DEFAULT 0.00,
  `total_proteins` decimal(10,2) DEFAULT 0.00,
  `total_carbohydrates` decimal(10,2) DEFAULT 0.00,
  `total_fats` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `recipe_nutrition`
--

INSERT INTO `recipe_nutrition` (`recipe_id`, `age_group`, `total_calories`, `total_proteins`, `total_carbohydrates`, `total_fats`) VALUES
(1, 'PREESCOLAR', 2.33, 0.20, 0.02, 0.17),
(1, 'PRIMARIA_A', 2.33, 0.20, 0.02, 0.17),
(1, 'PRIMARIA_B', 2.33, 0.20, 0.02, 0.17),
(1, 'SECUNDARIA', 2.79, 0.23, 0.02, 0.20),
(2, 'PREESCOLAR', 0.20, 0.03, 0.01, 0.00),
(2, 'PRIMARIA_A', 0.20, 0.03, 0.01, 0.00),
(2, 'PRIMARIA_B', 0.20, 0.03, 0.01, 0.00),
(2, 'SECUNDARIA', 0.72, 0.05, 0.11, 0.01),
(3, 'PREESCOLAR', 0.21, 0.04, 0.00, 0.00),
(3, 'PRIMARIA_A', 0.23, 0.04, 0.00, 0.01),
(3, 'PRIMARIA_B', 0.25, 0.05, 0.00, 0.01),
(3, 'SECUNDARIA', 0.33, 0.06, 0.00, 0.01),
(4, 'PREESCOLAR', 0.58, 0.13, 0.00, 0.00),
(4, 'PRIMARIA_A', 0.70, 0.15, 0.00, 0.00),
(4, 'PRIMARIA_B', 0.81, 0.18, 0.00, 0.01),
(4, 'SECUNDARIA', 0.93, 0.20, 0.00, 0.01);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `created_at`) VALUES
(1, 'SUPER_ADMIN', 'Acceso total al sistema', '2026-02-01 00:04:11'),
(2, 'ADMIN_CENTRAL', 'Administrador central del PAE', '2026-02-01 00:04:11'),
(3, 'OPERADOR_LOGISTICO', 'Encargado de inventario y logística', '2026-02-01 00:04:11'),
(4, 'RECTOR_SEDE', 'Validación en instituciones educativas', '2026-02-01 00:04:11'),
(5, 'MANIPULADORA', 'Registro de consumo y preparación', '2026-02-01 00:04:11'),
(6, 'PAE_ADMIN', 'Administrador total de un Programa PAE específico', '2026-02-01 00:46:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `dane_code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `rector` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `logo_path` varchar(255) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `municipality` varchar(100) DEFAULT NULL,
  `school_type` enum('PUBLICO','PRIVADO','MIXTO','INDIGENA') DEFAULT 'PUBLICO',
  `area_type` enum('URBANA','RURAL') DEFAULT 'URBANA',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `schools`
--

INSERT INTO `schools` (`id`, `pae_id`, `dane_code`, `name`, `rector`, `address`, `phone`, `email`, `logo_path`, `department`, `municipality`, `school_type`, `area_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '47001220022', 'COLEGIO DE PRUEBA', 'PEDRO PEREZ PRIETO', 'Calle 6C No 19-21 Campito', '3003892753', 'colegio@mail.com', 'assets/img/logos/school_697f6aca3ae1f.jpg', 'MAGDALENA', 'SANTA MARTA', 'PUBLICO', 'URBANA', 'active', '2026-02-01 15:01:30', '2026-02-01 16:37:51'),
(5, 3, '470010001234', 'INSTITUCIÓN EDUCATIVA SIMÓN BOLÍVAR', 'MARÍA FERNANDA LÓPEZ GARCÍA', 'CALLE 22 # 15-30 BARRIO CENTRO', '3001234567', 'rectoria.bolivar@educacion.gov.co', NULL, 'MAGDALENA', 'SANTA MARTA', '', 'URBANA', 'active', '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(8, 3, '470010009012', 'INSTITUCIÓN EDUCATIVA JOSÉ PRUDENCIO PADILLA', 'LUIS ALBERTO RAMÍREZ CASTRO', 'AVENIDA DEL RÍO # 45-12 BARRIO MAMATOCO', '3112345678', 'rectoria.padilla@educacion.gov.co', NULL, 'MAGDALENA', 'SANTA MARTA', 'PUBLICO', 'URBANA', 'active', '2026-02-01 21:17:14', '2026-02-01 21:17:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `school_branches`
--

CREATE TABLE `school_branches` (
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `dane_code` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `manager_name` varchar(255) DEFAULT NULL,
  `area_type` enum('URBANA','RURAL') DEFAULT 'URBANA',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `school_branches`
--

INSERT INTO `school_branches` (`id`, `school_id`, `pae_id`, `dane_code`, `name`, `address`, `phone`, `manager_name`, `area_type`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 3, '470012020002', 'PRINCIPAL', 'Calle 6C No 19-21 Campito', '3003892753', 'PEDRO PEREZ GGGGG', 'URBANA', 'active', '2026-02-01 15:01:30', '2026-02-01 16:39:18'),
(2, 1, 3, '470012020003', 'SEDE NO. 1', 'vereda la prueba', '5566', 'JUAN DE LOS PALOS', 'RURAL', 'active', '2026-02-01 15:10:22', '2026-02-01 16:39:32'),
(9, 5, 3, '470010001234', 'PRINCIPAL', 'CALLE 22 # 15-30 BARRIO CENTRO', '3001234567', 'MARÍA FERNANDA LÓPEZ GARCÍA', 'URBANA', 'active', '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(10, 5, 3, '470010005678', 'SEDE GAIRA', 'CARRERA 10 # 8-45 GAIRA', '3009876543', 'CARLOS ANDRÉS MARTÍNEZ RUIZ', 'RURAL', 'active', '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(17, 8, 3, '470010009012', 'PRINCIPAL', 'AVENIDA DEL RÍO # 45-12 BARRIO MAMATOCO', '3112345678', 'LUIS ALBERTO RAMÍREZ CASTRO', 'URBANA', 'active', '2026-02-01 21:17:14', '2026-02-01 21:17:14');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `suppliers`
--

CREATE TABLE `suppliers` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
  `nit` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `contact_person` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `type` enum('NATURAL','JURIDICA') DEFAULT 'JURIDICA',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `suppliers`
--

INSERT INTO `suppliers` (`id`, `pae_id`, `nit`, `name`, `contact_person`, `phone`, `email`, `address`, `city`, `type`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, '900900900-1', 'PROVEEDOR UNO', 'JUAN MARIA ORTEGA', '5566', 'provee@mail.com', 'Calle 1 No 2', 'SANTA MARTA', 'JURIDICA', 'active', '2026-02-01 15:58:43', '2026-02-09 12:45:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `pae_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `role_id`, `pae_id`, `username`, `email`, `password_hash`, `full_name`, `address`, `phone`, `status`, `created_at`) VALUES
(1, 1, NULL, 'admin', 'admin@pae.gov.co', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrador', NULL, NULL, 'active', '2026-02-01 00:04:12'),
(4, 6, 3, 'paestamta@mail.com', 'paestamta@mail.com', '$2y$10$RxFrF3gp9J0xUbMt89cxYOKbVBLvj6ZTgRO8xpALDJ/wiXBwZ7yHm', 'Admin PAE SANTA MARTA', '', '', 'active', '2026-02-01 13:50:33'),
(5, 2, 3, 'testuser', 'test@test.com', '$2y$10$/ESBPKMs2iFeHSe7/Rvh1uz35gKGZoDpNB4vSBpzPGX7ppA4vtKcW', 'Test User', NULL, NULL, 'active', '2026-02-06 13:32:32');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pae_id` (`pae_id`,`document_number`),
  ADD UNIQUE KEY `qr_uuid` (`qr_uuid`),
  ADD KEY `document_type_id` (`document_type_id`),
  ADD KEY `ethnic_group_id` (`ethnic_group_id`),
  ADD KEY `idx_beneficiaries_pae` (`pae_id`),
  ADD KEY `idx_beneficiaries_branch` (`branch_id`),
  ADD KEY `idx_beneficiaries_document` (`document_number`),
  ADD KEY `idx_beneficiaries_status` (`status`);

--
-- Indices de la tabla `cycle_projections`
--
ALTER TABLE `cycle_projections`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_projection` (`cycle_id`,`branch_id`,`item_id`),
  ADD KEY `idx_proj_cycle` (`cycle_id`),
  ADD KEY `idx_proj_branch` (`branch_id`),
  ADD KEY `idx_proj_item` (`item_id`);

--
-- Indices de la tabla `cycle_templates`
--
ALTER TABLE `cycle_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_template_pae` (`pae_id`);

--
-- Indices de la tabla `cycle_template_days`
--
ALTER TABLE `cycle_template_days`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_meal_per_day` (`template_id`,`day_number`,`meal_type`),
  ADD KEY `recipe_id` (`recipe_id`);

--
-- Indices de la tabla `daily_consumptions`
--
ALTER TABLE `daily_consumptions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_consumption` (`beneficiary_id`,`date`,`meal_type`),
  ADD KEY `idx_consumption_date` (`date`),
  ADD KEY `idx_consumption_branch` (`branch_id`),
  ADD KEY `pae_id` (`pae_id`);

--
-- Indices de la tabla `document_types`
--
ALTER TABLE `document_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `ethnic_groups`
--
ALTER TABLE `ethnic_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `food_groups`
--
ALTER TABLE `food_groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `hr_employees`
--
ALTER TABLE `hr_employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pae_id` (`pae_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Indices de la tabla `hr_positions`
--
ALTER TABLE `hr_positions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pae_id` (`pae_id`);

--
-- Indices de la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_inventory` (`pae_id`,`item_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pae_id` (`pae_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `idx_cycle` (`cycle_id`);

--
-- Indices de la tabla `inventory_movement_details`
--
ALTER TABLE `inventory_movement_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `movement_id` (`movement_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `inventory_quotes`
--
ALTER TABLE `inventory_quotes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pae_id` (`pae_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indices de la tabla `inventory_quote_details`
--
ALTER TABLE `inventory_quote_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quote_id` (`quote_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `inventory_remissions`
--
ALTER TABLE `inventory_remissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_remission_pae` (`pae_id`,`remission_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `fk_remission_cycle` (`cycle_id`),
  ADD KEY `fk_remission_po` (`po_id`),
  ADD KEY `fk_remission_supplier` (`supplier_id`);

--
-- Indices de la tabla `inventory_remission_details`
--
ALTER TABLE `inventory_remission_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `remission_id` (`remission_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_item_per_pae` (`pae_id`,`code`),
  ADD KEY `measurement_unit_id` (`measurement_unit_id`),
  ADD KEY `idx_items_pae` (`pae_id`),
  ADD KEY `idx_items_group` (`food_group_id`),
  ADD KEY `idx_items_status` (`status`);

--
-- Indices de la tabla `item_cycle_costs`
--
ALTER TABLE `item_cycle_costs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_item_cycle` (`pae_id`,`item_id`,`cycle_id`),
  ADD KEY `idx_pae_cycle` (`pae_id`,`cycle_id`),
  ADD KEY `idx_item` (`item_id`),
  ADD KEY `idx_cycle` (`cycle_id`);

--
-- Indices de la tabla `measurement_units`
--
ALTER TABLE `measurement_units`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indices de la tabla `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menus_pae` (`pae_id`),
  ADD KEY `idx_menus_cycle` (`cycle_id`),
  ADD KEY `idx_menus_age` (`age_group`);

--
-- Indices de la tabla `menu_cycles`
--
ALTER TABLE `menu_cycles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `validated_by` (`validated_by`),
  ADD KEY `idx_cycles_pae` (`pae_id`);

--
-- Indices de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_item_per_menu` (`menu_id`,`item_id`),
  ADD KEY `idx_menu_items_menu` (`menu_id`),
  ADD KEY `idx_menu_items_item` (`item_id`);

--
-- Indices de la tabla `menu_recipes`
--
ALTER TABLE `menu_recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_menu_recipes_menu` (`menu_id`),
  ADD KEY `idx_menu_recipes_recipe` (`recipe_id`);

--
-- Indices de la tabla `modules`
--
ALTER TABLE `modules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `group_id` (`group_id`);

--
-- Indices de la tabla `module_groups`
--
ALTER TABLE `module_groups`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_role_module_pae` (`role_id`,`module_id`,`pae_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `module_id` (`module_id`),
  ADD KEY `idx_perm_pae` (`pae_id`);

--
-- Indices de la tabla `nutritional_parameters`
--
ALTER TABLE `nutritional_parameters`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_params` (`age_group`,`meal_type`);

--
-- Indices de la tabla `pae_programs`
--
ALTER TABLE `pae_programs`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pae_ration_types`
--
ALTER TABLE `pae_ration_types`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pae_id` (`pae_id`);

--
-- Indices de la tabla `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_po_pae` (`pae_id`,`po_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `quote_id` (`quote_id`),
  ADD KEY `fk_po_cycle` (`cycle_id`);

--
-- Indices de la tabla `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `po_id` (`po_id`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_recipe_pae` (`pae_id`),
  ADD KEY `idx_recipe_type` (`meal_type`);

--
-- Indices de la tabla `recipe_items`
--
ALTER TABLE `recipe_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_item_recipe_group` (`recipe_id`,`item_id`,`age_group`),
  ADD KEY `item_id` (`item_id`);

--
-- Indices de la tabla `recipe_nutrition`
--
ALTER TABLE `recipe_nutrition`
  ADD PRIMARY KEY (`recipe_id`,`age_group`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indices de la tabla `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_schools_pae` (`pae_id`);

--
-- Indices de la tabla `school_branches`
--
ALTER TABLE `school_branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_branches_school` (`school_id`),
  ADD KEY `idx_branches_pae` (`pae_id`);

--
-- Indices de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_nit_pae` (`nit`,`pae_id`),
  ADD KEY `idx_suppliers_nit` (`nit`),
  ADD KEY `idx_suppliers_pae` (`pae_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `fk_user_pae` (`pae_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=91;

--
-- AUTO_INCREMENT de la tabla `cycle_projections`
--
ALTER TABLE `cycle_projections`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `cycle_templates`
--
ALTER TABLE `cycle_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `cycle_template_days`
--
ALTER TABLE `cycle_template_days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT de la tabla `daily_consumptions`
--
ALTER TABLE `daily_consumptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `document_types`
--
ALTER TABLE `document_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `ethnic_groups`
--
ALTER TABLE `ethnic_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `food_groups`
--
ALTER TABLE `food_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `hr_employees`
--
ALTER TABLE `hr_employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `hr_positions`
--
ALTER TABLE `hr_positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_movement_details`
--
ALTER TABLE `inventory_movement_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_quotes`
--
ALTER TABLE `inventory_quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventory_quote_details`
--
ALTER TABLE `inventory_quote_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `inventory_remissions`
--
ALTER TABLE `inventory_remissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `inventory_remission_details`
--
ALTER TABLE `inventory_remission_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT de la tabla `item_cycle_costs`
--
ALTER TABLE `item_cycle_costs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `measurement_units`
--
ALTER TABLE `measurement_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=311;

--
-- AUTO_INCREMENT de la tabla `menu_cycles`
--
ALTER TABLE `menu_cycles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT de la tabla `menu_recipes`
--
ALTER TABLE `menu_recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT de la tabla `module_groups`
--
ALTER TABLE `module_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `module_permissions`
--
ALTER TABLE `module_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT de la tabla `nutritional_parameters`
--
ALTER TABLE `nutritional_parameters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `pae_programs`
--
ALTER TABLE `pae_programs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pae_ration_types`
--
ALTER TABLE `pae_ration_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `recipe_items`
--
ALTER TABLE `recipe_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `school_branches`
--
ALTER TABLE `school_branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beneficiaries_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `school_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `beneficiaries_ibfk_3` FOREIGN KEY (`document_type_id`) REFERENCES `document_types` (`id`),
  ADD CONSTRAINT `beneficiaries_ibfk_4` FOREIGN KEY (`ethnic_group_id`) REFERENCES `ethnic_groups` (`id`);

--
-- Filtros para la tabla `cycle_projections`
--
ALTER TABLE `cycle_projections`
  ADD CONSTRAINT `cycle_projections_ibfk_1` FOREIGN KEY (`cycle_id`) REFERENCES `menu_cycles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cycle_projections_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `school_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cycle_projections_ibfk_3` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cycle_templates`
--
ALTER TABLE `cycle_templates`
  ADD CONSTRAINT `cycle_templates_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `cycle_template_days`
--
ALTER TABLE `cycle_template_days`
  ADD CONSTRAINT `cycle_template_days_ibfk_1` FOREIGN KEY (`template_id`) REFERENCES `cycle_templates` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cycle_template_days_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`);

--
-- Filtros para la tabla `daily_consumptions`
--
ALTER TABLE `daily_consumptions`
  ADD CONSTRAINT `daily_consumptions_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_consumptions_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `school_branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `daily_consumptions_ibfk_3` FOREIGN KEY (`beneficiary_id`) REFERENCES `beneficiaries` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `hr_employees`
--
ALTER TABLE `hr_employees`
  ADD CONSTRAINT `hr_employees_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `hr_positions` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inventory`
--
ALTER TABLE `inventory`
  ADD CONSTRAINT `inventory_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movements_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventory_movements_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `inventory_movement_details`
--
ALTER TABLE `inventory_movement_details`
  ADD CONSTRAINT `inventory_movement_details_ibfk_1` FOREIGN KEY (`movement_id`) REFERENCES `inventory_movements` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_movement_details_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventory_quotes`
--
ALTER TABLE `inventory_quotes`
  ADD CONSTRAINT `inventory_quotes_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_quotes_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventory_quotes_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `inventory_quote_details`
--
ALTER TABLE `inventory_quote_details`
  ADD CONSTRAINT `inventory_quote_details_ibfk_1` FOREIGN KEY (`quote_id`) REFERENCES `inventory_quotes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_quote_details_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Filtros para la tabla `inventory_remissions`
--
ALTER TABLE `inventory_remissions`
  ADD CONSTRAINT `fk_remission_cycle` FOREIGN KEY (`cycle_id`) REFERENCES `menu_cycles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_remission_po` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_remission_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_remissions_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_remissions_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `inventory_remissions_ibfk_3` FOREIGN KEY (`branch_id`) REFERENCES `school_branches` (`id`);

--
-- Filtros para la tabla `inventory_remission_details`
--
ALTER TABLE `inventory_remission_details`
  ADD CONSTRAINT `inventory_remission_details_ibfk_1` FOREIGN KEY (`remission_id`) REFERENCES `inventory_remissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `inventory_remission_details_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Filtros para la tabla `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `items_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `items_ibfk_2` FOREIGN KEY (`food_group_id`) REFERENCES `food_groups` (`id`),
  ADD CONSTRAINT `items_ibfk_3` FOREIGN KEY (`measurement_unit_id`) REFERENCES `measurement_units` (`id`);

--
-- Filtros para la tabla `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menus_ibfk_2` FOREIGN KEY (`cycle_id`) REFERENCES `menu_cycles` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `menu_cycles`
--
ALTER TABLE `menu_cycles`
  ADD CONSTRAINT `menu_cycles_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_cycles_ibfk_2` FOREIGN KEY (`validated_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `menu_recipes`
--
ALTER TABLE `menu_recipes`
  ADD CONSTRAINT `menu_recipes_ibfk_1` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `menu_recipes_ibfk_2` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `fk_module_group` FOREIGN KEY (`group_id`) REFERENCES `module_groups` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `module_permissions`
--
ALTER TABLE `module_permissions`
  ADD CONSTRAINT `fk_perm_module` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_perm_pae` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_perm_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `fk_po_cycle` FOREIGN KEY (`cycle_id`) REFERENCES `menu_cycles` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_orders_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_orders_ibfk_3` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`),
  ADD CONSTRAINT `purchase_orders_ibfk_4` FOREIGN KEY (`quote_id`) REFERENCES `inventory_quotes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  ADD CONSTRAINT `purchase_order_details_ibfk_1` FOREIGN KEY (`po_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_details_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`);

--
-- Filtros para la tabla `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recipe_items`
--
ALTER TABLE `recipe_items`
  ADD CONSTRAINT `recipe_items_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `recipe_items_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `items` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `recipe_nutrition`
--
ALTER TABLE `recipe_nutrition`
  ADD CONSTRAINT `recipe_nutrition_ibfk_1` FOREIGN KEY (`recipe_id`) REFERENCES `recipes` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `schools`
--
ALTER TABLE `schools`
  ADD CONSTRAINT `schools_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `school_branches`
--
ALTER TABLE `school_branches`
  ADD CONSTRAINT `school_branches_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `school_branches_ibfk_2` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_ibfk_1` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_user_pae` FOREIGN KEY (`pae_id`) REFERENCES `pae_programs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_user_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
