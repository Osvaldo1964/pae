-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 04-02-2026 a las 01:23:12
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
  `ration_type` enum('COMPLEMENTO MAÑANA','COMPLEMENTO TARDE','ALMUERZO') DEFAULT 'ALMUERZO',
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

INSERT INTO `beneficiaries` (`id`, `pae_id`, `branch_id`, `document_type_id`, `document_number`, `first_name`, `second_name`, `last_name1`, `last_name2`, `birth_date`, `enrollment_date`, `gender`, `shift`, `grade`, `group_name`, `group_letter`, `ethnic_group_id`, `sisben_category`, `is_overage`, `disability_type`, `is_disabled`, `is_victim`, `is_migrant`, `address`, `phone`, `email`, `guardian_name`, `guardian_relationship`, `guardian_phone`, `guardian_address`, `guardian_email`, `status`, `modality`, `ration_type`, `data_authorization`, `medical_restrictions`, `observations`, `simat_id`, `qr_uuid`, `biometric_hash`, `created_at`, `updated_at`) VALUES
(31, 3, 9, 2, '1098765432', 'JUAN', 'CARLOS', 'PÉREZ', 'GONZÁLEZ', '2012-03-15', '2024-01-15', 'MASCULINO', 'MAÑANA', '6', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(32, 3, 9, 2, '1098765433', 'MARÍA', 'FERNANDA', 'RODRÍGUEZ', 'LÓPEZ', '2012-07-22', '2024-01-15', 'FEMENINO', 'MAÑANA', '6', 'A', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(33, 3, 9, 2, '1098765434', 'CARLOS', 'ANDRÉS', 'MARTÍNEZ', 'RUIZ', '2011-11-08', '2024-01-15', 'MASCULINO', 'MAÑANA', '7', 'B', NULL, 2, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(34, 3, 9, 1, '1234567890', 'ANA', 'SOFÍA', 'GARCÍA', 'HERNÁNDEZ', '2014-05-12', '2024-01-15', 'FEMENINO', 'MAÑANA', '4', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO MAÑANA', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-03 23:04:20'),
(35, 3, 9, 1, '1234567891', 'LUIS', 'FERNANDO', 'TORRES', 'SÁNCHEZ', '2015-09-30', '2024-01-15', 'MASCULINO', 'MAÑANA', '3', 'B', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO MAÑANA', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-03 23:04:20'),
(36, 3, 9, 2, '1098765435', 'CAMILA', 'ANDREA', 'DÍAZ', 'MORENO', '2013-02-18', '2024-01-15', 'FEMENINO', 'MAÑANA', '5', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(37, 3, 9, 2, '1098765436', 'DIEGO', 'ALEJANDRO', 'RAMÍREZ', 'CASTRO', '2012-12-05', '2024-01-15', 'MASCULINO', 'MAÑANA', '6', 'B', NULL, 6, 'B2', 0, 'NINGUNA', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(38, 3, 9, 1, '1234567892', 'VALENTINA', '', 'GÓMEZ', 'VARGAS', '2016-04-20', '2024-01-15', 'FEMENINO', 'MAÑANA', '2', 'A', NULL, 2, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO MAÑANA', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-03 23:04:20'),
(39, 3, 9, 2, '1098765437', 'SANTIAGO', 'JOSÉ', 'MENDOZA', 'ORTIZ', '2011-06-14', '2024-01-15', 'MASCULINO', 'MAÑANA', '7', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(40, 3, 9, 1, '1234567893', 'ISABELLA', 'MARÍA', 'JIMÉNEZ', 'REYES', '2015-01-25', '2024-01-15', 'FEMENINO', 'MAÑANA', '3', 'A', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO MAÑANA', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-03 23:04:20'),
(41, 3, 9, 2, '1098765438', 'ANDRÉS', 'FELIPE', 'CRUZ', 'NAVARRO', '2013-08-09', '2024-01-15', 'MASCULINO', 'MAÑANA', '5', 'B', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(42, 3, 9, 1, '1234567894', 'SOFÍA', 'ALEJANDRA', 'PARRA', 'SILVA', '2016-10-03', '2024-01-15', 'FEMENINO', 'MAÑANA', '2', 'B', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO MAÑANA', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-03 23:04:20'),
(43, 3, 9, 2, '1098765439', 'MATEO', 'DAVID', 'ROJAS', 'GUTIÉRREZ', '2012-04-28', '2024-01-15', 'MASCULINO', 'MAÑANA', '6', 'A', NULL, 2, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(44, 3, 9, 1, '1234567895', 'LUCÍA', 'VALENTINA', 'MORALES', 'PEÑA', '2017-02-14', '2024-01-15', 'FEMENINO', 'MAÑANA', '1', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO MAÑANA', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-03 23:04:20'),
(45, 3, 9, 2, '1098765440', 'NICOLÁS', 'EDUARDO', 'VEGA', 'RÍOS', '2011-09-19', '2024-01-15', 'MASCULINO', 'MAÑANA', '7', 'B', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'ALMUERZO', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(46, 3, 10, 2, '1098765441', 'GABRIELA', 'ANDREA', 'CASTRO', 'MUÑOZ', '2012-01-10', '2024-01-15', 'FEMENINO', 'TARDE', '6', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(47, 3, 10, 2, '1098765442', 'DANIEL', 'ESTEBAN', 'HERRERA', 'LEÓN', '2011-05-22', '2024-01-15', 'MASCULINO', 'TARDE', '7', 'A', NULL, 2, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(48, 3, 10, 1, '1234567896', 'PAULA', 'CRISTINA', 'SALAZAR', 'CORTÉS', '2014-11-08', '2024-01-15', 'FEMENINO', 'TARDE', '4', 'B', NULL, 6, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(49, 3, 10, 2, '1098765443', 'SEBASTIÁN', 'CAMILO', 'AGUILAR', 'RAMOS', '2013-03-16', '2024-01-15', 'MASCULINO', 'TARDE', '5', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(50, 3, 10, 1, '1234567897', 'MARIANA', 'ISABEL', 'OSPINA', 'MEJÍA', '2015-07-29', '2024-01-15', 'FEMENINO', 'TARDE', '3', 'A', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(51, 3, 10, 2, '1098765444', 'EMILIO', 'ANTONIO', 'SUÁREZ', 'BLANCO', '2012-09-11', '2024-01-15', 'MASCULINO', 'TARDE', '6', 'B', NULL, 2, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(52, 3, 10, 1, '1234567898', 'CAROLINA', 'SOFÍA', 'MEDINA', 'ROJAS', '2016-12-04', '2024-01-15', 'FEMENINO', 'TARDE', '2', 'A', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(53, 3, 10, 2, '1098765445', 'MIGUEL', 'ÁNGEL', 'VARGAS', 'SANTOS', '2011-02-27', '2024-01-15', 'MASCULINO', 'TARDE', '7', 'B', NULL, 6, 'A2', 0, 'NINGUNA', 0, 1, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(54, 3, 10, 1, '1234567899', 'VALERIA', 'NICOLE', 'RÍOS', 'FERNÁNDEZ', '2017-06-18', '2024-01-15', 'FEMENINO', 'TARDE', '1', 'B', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(55, 3, 10, 2, '1098765446', 'RICARDO', 'JAVIER', 'NÚÑEZ', 'CÁRDENAS', '2013-10-05', '2024-01-15', 'MASCULINO', 'TARDE', '5', 'B', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(56, 3, 10, 1, '1234567900', 'NATALIA', 'PAOLA', 'MOLINA', 'ARANGO', '2015-04-13', '2024-01-15', 'FEMENINO', 'TARDE', '3', 'B', NULL, 2, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(57, 3, 10, 2, '1098765447', 'ALEJANDRO', 'MANUEL', 'QUINTERO', 'PALACIOS', '2012-08-21', '2024-01-15', 'MASCULINO', 'TARDE', '6', 'A', NULL, 6, 'B2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(58, 3, 10, 1, '1234567901', 'DANIELA', 'MARCELA', 'ESCOBAR', 'GÓMEZ', '2016-01-30', '2024-01-15', 'FEMENINO', 'TARDE', '2', 'B', NULL, 6, 'A2', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(59, 3, 10, 2, '1098765448', 'FELIPE', 'IGNACIO', 'LARA', 'MENDOZA', '2011-12-17', '2024-01-15', 'MASCULINO', 'TARDE', '7', 'A', NULL, 6, 'A1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28'),
(60, 3, 10, 1, '1234567902', 'JULIANA', 'BEATRIZ', 'CANO', 'VALENCIA', '2017-05-09', '2024-01-15', 'FEMENINO', 'TARDE', '1', 'A', NULL, 6, 'B1', 0, 'NINGUNA', 0, 0, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'ACTIVO', 'RACION PREPARADA EN SITIO', 'COMPLEMENTO TARDE', 1, NULL, NULL, NULL, NULL, NULL, '2026-02-01 21:02:28', '2026-02-01 21:02:28');

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
(1, 3, 'ciclo regular', '2026-02-02 02:38:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cycle_template_days`
--

CREATE TABLE `cycle_template_days` (
  `id` int(11) NOT NULL,
  `template_id` int(11) NOT NULL,
  `day_number` int(11) NOT NULL,
  `meal_type` enum('DESAYUNO','ALMUERZO') NOT NULL,
  `recipe_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `cycle_template_days`
--

INSERT INTO `cycle_template_days` (`id`, `template_id`, `day_number`, `meal_type`, `recipe_id`) VALUES
(1, 1, 1, 'DESAYUNO', 1),
(2, 1, 1, 'ALMUERZO', 2),
(3, 1, 2, 'DESAYUNO', 4),
(4, 1, 2, 'ALMUERZO', 3),
(5, 1, 3, 'DESAYUNO', 1),
(6, 1, 3, 'ALMUERZO', 3),
(7, 1, 4, 'DESAYUNO', 1),
(8, 1, 4, 'ALMUERZO', 2),
(9, 1, 5, 'DESAYUNO', 1),
(10, 1, 6, 'DESAYUNO', 4),
(11, 1, 7, 'DESAYUNO', 4);

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
  `user_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `items`
--

INSERT INTO `items` (`id`, `pae_id`, `code`, `name`, `description`, `food_group_id`, `measurement_unit_id`, `gross_weight`, `net_weight`, `waste_percentage`, `calories`, `proteins`, `carbohydrates`, `fats`, `fiber`, `iron`, `calcium`, `sodium`, `vitamin_a`, `vitamin_c`, `contains_gluten`, `contains_lactose`, `contains_peanuts`, `contains_seafood`, `contains_eggs`, `contains_soy`, `is_local_purchase`, `local_producer`, `sanitary_registry`, `requires_refrigeration`, `shelf_life_days`, `unit_cost`, `last_purchase_date`, `status`, `created_at`, `updated_at`) VALUES
(265, 3, 'ARROZ', 'ARROZ BLANCO', NULL, 1, 1, 1000.00, 1000.00, 0.00, 130.00, 2.70, 28.00, 0.30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(266, 3, 'PASTA', 'PASTA ESPAGUETI', NULL, 1, 1, 1000.00, 1000.00, 0.00, 157.00, 5.80, 30.00, 0.90, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(267, 3, 'PAPA', 'PAPA SABANERA', NULL, 1, 1, 1000.00, 850.00, 15.00, 77.00, 2.00, 17.00, 0.10, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(268, 3, 'YUCA', 'YUCA FRESCA', NULL, 1, 1, 1000.00, 750.00, 25.00, 160.00, 1.40, 38.00, 0.30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(269, 3, 'PLATANO', 'PLATANO MADURO', NULL, 1, 1, 1000.00, 650.00, 35.00, 122.00, 1.30, 32.00, 0.40, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(270, 3, 'AREPA', 'AREPA DE MAIZ', NULL, 1, 5, 1.00, 1.00, 0.00, 218.00, 4.50, 45.00, 1.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(271, 3, 'PAN', 'PAN TAJADO INTEGRAL', NULL, 1, 5, 1.00, 1.00, 0.00, 247.00, 8.50, 41.00, 3.50, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(272, 3, 'AVENA', 'AVENA EN HOJUELAS', NULL, 1, 1, 1000.00, 1000.00, 0.00, 389.00, 16.00, 66.00, 6.90, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(273, 3, 'GALLETA', 'GALLETA INTEGRAL', NULL, 1, 5, 1.00, 1.00, 0.00, 450.00, 7.00, 70.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(274, 3, 'TOSTADA', 'TOSTADA DE ARROZ', NULL, 1, 5, 1.00, 1.00, 0.00, 380.00, 8.00, 80.00, 2.50, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(275, 3, 'POLLO', 'PECHUGA DE POLLO', NULL, 2, 1, 1000.00, 850.00, 15.00, 165.00, 31.00, 0.00, 3.60, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(276, 3, 'CARNE', 'CARNE DE RES (MURILLO)', NULL, 2, 1, 1000.00, 950.00, 5.00, 250.00, 26.00, 0.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(277, 3, 'CERDO', 'LOMO DE CERDO', NULL, 2, 1, 1000.00, 950.00, 5.00, 242.00, 27.00, 0.00, 14.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(278, 3, 'HUEVO', 'HUEVO AA FRESCO', NULL, 2, 5, 60.00, 50.00, 16.00, 155.00, 13.00, 1.10, 11.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 1, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(279, 3, 'FRIJOL', 'FRIJOL CARGAMANTO', NULL, 2, 1, 1000.00, 1000.00, 0.00, 333.00, 23.00, 60.00, 1.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(280, 3, 'LENTEJA', 'LENTEJA SECA', NULL, 2, 1, 1000.00, 1000.00, 0.00, 352.00, 25.00, 63.00, 1.10, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(281, 3, 'GARBANZO', 'GARBANZO SECO', NULL, 2, 1, 1000.00, 1000.00, 0.00, 364.00, 19.00, 61.00, 6.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(282, 3, 'PESCADO', 'FILETE DE TILAPIA', NULL, 2, 1, 1000.00, 900.00, 10.00, 96.00, 20.00, 0.00, 1.70, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(283, 3, 'ATUN', 'ATUN EN AGUA (LATA)', NULL, 2, 1, 170.00, 120.00, 30.00, 116.00, 25.00, 0.00, 0.80, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(284, 3, 'ARVEJA', 'ARVEJA SECA', NULL, 2, 1, 1000.00, 1000.00, 0.00, 341.00, 24.00, 60.00, 1.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(285, 3, 'BLANQUILLO', 'FRIJOL BLANQUILLO', NULL, 2, 1, 1000.00, 1000.00, 0.00, 337.00, 23.00, 60.00, 1.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(286, 3, 'QUESO', 'QUESO CAMPESINO', NULL, 3, 1, 1000.00, 1000.00, 0.00, 264.00, 18.00, 3.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(287, 3, 'LECHE', 'LECHE ENTERA PASTEURIZADA', NULL, 3, 3, 100.00, 100.00, 0.00, 61.00, 3.20, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(288, 3, 'YOGURT', 'YOGURT NATURAL', NULL, 3, 3, 100.00, 100.00, 0.00, 59.00, 3.50, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(289, 3, 'KUMIS', 'KUMIS FRESCO', NULL, 3, 3, 100.00, 100.00, 0.00, 78.00, 3.30, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(290, 3, 'AVENALAC', 'AVENA CON LECHE PREPARADA', NULL, 3, 3, 100.00, 100.00, 0.00, 120.00, 4.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 1, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(291, 3, 'MATANTE', 'BIENESTARINA MASAMORRA', NULL, 3, 3, 100.00, 100.00, 0.00, 110.00, 5.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(292, 3, 'ZANAHORIA', 'ZANAHORIA FRESCA', NULL, 5, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(293, 3, 'TOMATE', 'TOMATE CHONTO', NULL, 5, 1, 100.00, 100.00, 5.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(294, 3, 'CEBOLLA', 'CEBOLLA CABEZONA', NULL, 5, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(295, 3, 'HABICHUELA', 'HABICHUELA VERDE', NULL, 5, 1, 100.00, 100.00, 8.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(296, 3, 'REPOLLO', 'REPOLLO BLANCO', NULL, 5, 1, 100.00, 100.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(297, 3, 'TOMATEA', 'TOMATE DE ARBOL', NULL, 4, 1, 100.00, 100.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(298, 3, 'GUAYABA', 'GUAYABA PERA', NULL, 4, 1, 100.00, 100.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(299, 3, 'NARANJA', 'NARANJA TANGERINA', NULL, 4, 1, 100.00, 100.00, 40.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(300, 3, 'MANZANA', 'MANZANA ROJA', NULL, 4, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(301, 3, 'BANANO', 'BANANO URABA', NULL, 4, 1, 100.00, 100.00, 35.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(302, 3, 'PAPAYA', 'PAPAYA MELON', NULL, 4, 1, 100.00, 100.00, 25.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(303, 3, 'AHUYAMA', 'AHUYAMA BOLO', NULL, 5, 1, 100.00, 100.00, 25.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(304, 3, 'ESPINACA', 'ESPINACA BOGOTANA', NULL, 5, 1, 100.00, 100.00, 15.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(305, 3, 'LECHUGA', 'LECHUGA BATAVIA', NULL, 5, 1, 100.00, 100.00, 20.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(306, 3, 'PEPINO', 'PEPINO COHOMBRO', NULL, 5, 1, 100.00, 100.00, 10.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(307, 3, 'PI├æA', 'PI├æA ORO MIEL', NULL, 4, 1, 100.00, 100.00, 45.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(308, 3, 'MORA', 'MORA DE CASTILLA', NULL, 4, 1, 100.00, 100.00, 5.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(309, 3, 'LIMON', 'LIMON TAHITI', NULL, 4, 1, 100.00, 100.00, 50.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(310, 3, 'ACEITE', 'ACEITE VEGETAL DE GIRASOL', NULL, 6, 3, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(311, 3, 'SAL', 'SAL REFINADA YODADA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(312, 3, 'AZUCAR', 'AZUCAR BLANCA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(313, 3, 'PANELA', 'PANELA REDONDA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(314, 3, 'AJO', 'AJO EN PASTA', NULL, 7, 1, 100.00, 100.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0.00, 0, 0, 0, 0, 0, 0, 0, NULL, NULL, 0, NULL, 0.00, NULL, 'ACTIVO', '2026-02-03 23:29:58', '2026-02-03 23:29:58');

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
  `meal_type` enum('DESAYUNO','MEDIA MAÑANA','ALMUERZO','ONCES','CENA') DEFAULT 'ALMUERZO',
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
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE `modules` (
  `id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `route_key` varchar(50) NOT NULL COMMENT 'Identificador para el router JS',
  `icon` varchar(50) DEFAULT 'fas fa-circle'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `modules`
--

INSERT INTO `modules` (`id`, `group_id`, `name`, `description`, `route_key`, `icon`) VALUES
(1, 1, 'Usuarios', 'Gestión de usuarios y accesos', 'users', 'fas fa-user-cog'),
(2, 1, 'Roles y Permisos', 'Configuración de perfiles de acceso', 'roles', 'fas fa-shield-alt'),
(3, 2, 'Sedes Educativas', 'Gestión de instituciones y sedes', 'sedes', 'fas fa-school'),
(4, 6, 'Proveedores', 'Directorio de proveedores', 'proveedores', 'fas fa-truck'),
(5, 3, 'Estudiantes', 'Base de datos de titulares de derecho', 'beneficiarios', 'fas fa-child'),
(6, 3, 'Novedades', 'Reporte de ausentismos y retiros', 'novedades', 'fas fa-exclamation-circle'),
(9, 5, 'Dashboards', 'Tableros de control gerencial', 'dashboard_kpi', 'fas fa-tachometer-alt'),
(18, 4, 'Ítems', 'Gestión de insumos e ingredientes', 'items', 'fas fa-apple-alt'),
(19, 4, 'Recetario', 'Maestro de recetas y platos base', 'recetario', 'fas fa-book-medical'),
(20, 4, 'Minutas', 'Planeación de menús y ciclos', 'minutas', 'fas fa-tasks'),
(21, 6, 'Almacén', 'Control de stock e inventario', 'almacen', 'fas fa-warehouse'),
(22, 6, 'Cotizaciones', 'Gestión de precios de proveedores', 'cotizaciones', 'fas fa-file-invoice-dollar'),
(23, 6, 'Órdenes de Compra', 'Gestión de pedidos a proveedores', 'compras', 'fas fa-shopping-cart'),
(24, 6, 'Remisiones', 'Control de entregas a sedes', 'remisiones', 'fas fa-truck-loading');

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
(5, 'Reportes', 'fas fa-chart-line', 6),
(6, 'Inventarios', 'fas fa-boxes', 5);

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
(49, 6, 3, 24, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `nutritional_parameters`
--

CREATE TABLE `nutritional_parameters` (
  `id` int(11) NOT NULL,
  `age_group` enum('PREESCOLAR','PRIMARIA_A','PRIMARIA_B','SECUNDARIA') NOT NULL,
  `meal_type` enum('DESAYUNO','MEDIA MAÑANA','ALMUERZO','ONCES','CENA') NOT NULL,
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

INSERT INTO `nutritional_parameters` (`id`, `age_group`, `meal_type`, `min_calories`, `max_calories`, `min_proteins`, `max_proteins`, `min_iron`, `min_calcium`, `max_sodium`, `max_egg_frequency`, `max_fried_frequency`, `created_at`, `updated_at`) VALUES
(1, 'PREESCOLAR', 'ALMUERZO', 300.00, 400.00, 10.00, 15.00, 2.50, 150.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-01 21:58:22'),
(2, 'PRIMARIA_A', 'ALMUERZO', 450.00, 550.00, 15.00, 20.00, 3.50, 200.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-03 23:02:42'),
(3, 'SECUNDARIA', 'ALMUERZO', 550.00, 700.00, 20.00, 30.00, 4.50, 250.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-03 23:02:42'),
(4, 'PREESCOLAR', 'MEDIA MAÑANA', 150.00, 200.00, 5.00, 8.00, 1.00, 100.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-01 21:58:22'),
(5, 'PRIMARIA_A', 'MEDIA MAÑANA', 200.00, 250.00, 8.00, 12.00, 1.50, 150.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-03 23:02:42'),
(6, 'SECUNDARIA', 'MEDIA MAÑANA', 250.00, 300.00, 10.00, 15.00, 2.00, 200.00, NULL, 2, 1, '2026-02-01 21:58:22', '2026-02-03 23:02:42'),
(7, 'PRIMARIA_B', 'ALMUERZO', 500.00, 600.00, 18.00, 25.00, 4.00, 220.00, NULL, 2, 1, '2026-02-03 23:29:58', '2026-02-03 23:29:58'),
(8, 'PRIMARIA_B', '', 220.00, 270.00, 9.00, 13.00, 1.80, 170.00, NULL, 2, 1, '2026-02-03 23:29:58', '2026-02-03 23:29:58');

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
-- Estructura de tabla para la tabla `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` int(11) NOT NULL,
  `pae_id` int(11) NOT NULL,
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
  `meal_type` enum('DESAYUNO','MEDIA MAÑANA','ALMUERZO','ONCES','CENA') NOT NULL,
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

INSERT INTO `recipes` (`id`, `pae_id`, `name`, `meal_type`, `description`, `total_calories`, `total_proteins`, `total_carbohydrates`, `total_fats`, `status`, `created_at`, `updated_at`) VALUES
(1, 3, 'HUEVOS REVUELTOS CON TOMATE Y CEBOLLA', 'DESAYUNO', 'HUEVOS REVUELTOS CON TOMATE Y CEBOLLA - CAFE CON LECHE - FRUTA', 2.79, 0.23, 0.02, 0.20, 'ACTIVO', '2026-02-01 23:27:08', '2026-02-03 23:49:10'),
(2, 3, 'Arroz con Pollo y Ensalada', 'ALMUERZO', 'ARROZ CON POLLO - ENSALADA - JUGO DE NARANJA', 0.72, 0.05, 0.11, 0.01, 'ACTIVO', '2026-02-02 01:00:26', '2026-02-03 23:48:36'),
(3, 3, 'SOPA DE POLLO', 'ALMUERZO', 'SOPA DE POLLO', 0.00, 0.00, 0.00, 0.00, 'ACTIVO', '2026-02-02 01:06:00', '2026-02-02 01:06:00'),
(4, 3, 'MOTE DE GUINEO VERDE', 'DESAYUNO', 'MOTE DE GUINEO - MANZANA - CAFE CON LECHE', 0.00, 0.00, 0.00, 0.00, 'ACTIVO', '2026-02-02 01:07:36', '2026-02-02 01:07:36');

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
(81, 1, 278, 'SECUNDARIA', 1.800, 'Caliente');

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
(3, 'PREESCOLAR', 0.00, 0.00, 0.00, 0.00),
(3, 'PRIMARIA_A', 0.00, 0.00, 0.00, 0.00),
(3, 'PRIMARIA_B', 0.00, 0.00, 0.00, 0.00),
(3, 'SECUNDARIA', 0.00, 0.00, 0.00, 0.00),
(4, 'PREESCOLAR', 0.00, 0.00, 0.00, 0.00),
(4, 'PRIMARIA_A', 0.00, 0.00, 0.00, 0.00),
(4, 'PRIMARIA_B', 0.00, 0.00, 0.00, 0.00),
(4, 'SECUNDARIA', 0.00, 0.00, 0.00, 0.00);

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
(1, 3, '900900900-1', 'PROVEEDOR UNO', 'JUAN MARIA ORTEGA', '5566', 'provee@mail.com', 'Calle 1 No 2', 'SANTA MARTA', 'JURIDICA', 'active', '2026-02-01 15:58:43', '2026-02-04 00:05:39');

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
(4, 6, 3, 'paestamta@mail.com', 'paestamta@mail.com', '$2y$10$RxFrF3gp9J0xUbMt89cxYOKbVBLvj6ZTgRO8xpALDJ/wiXBwZ7yHm', 'Admin PAE SANTA MARTA', '', '', 'active', '2026-02-01 13:50:33');

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
  ADD KEY `supplier_id` (`supplier_id`);

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
  ADD KEY `branch_id` (`branch_id`);

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
-- Indices de la tabla `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_po_pae` (`pae_id`,`po_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `supplier_id` (`supplier_id`),
  ADD KEY `quote_id` (`quote_id`);

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
-- AUTO_INCREMENT de la tabla `cycle_templates`
--
ALTER TABLE `cycle_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cycle_template_days`
--
ALTER TABLE `cycle_template_days`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
-- AUTO_INCREMENT de la tabla `inventory`
--
ALTER TABLE `inventory`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_quote_details`
--
ALTER TABLE `inventory_quote_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_remissions`
--
ALTER TABLE `inventory_remissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `inventory_remission_details`
--
ALTER TABLE `inventory_remission_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `items`
--
ALTER TABLE `items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=315;

--
-- AUTO_INCREMENT de la tabla `measurement_units`
--
ALTER TABLE `measurement_units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=221;

--
-- AUTO_INCREMENT de la tabla `menu_cycles`
--
ALTER TABLE `menu_cycles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT de la tabla `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=313;

--
-- AUTO_INCREMENT de la tabla `modules`
--
ALTER TABLE `modules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT de la tabla `module_groups`
--
ALTER TABLE `module_groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `module_permissions`
--
ALTER TABLE `module_permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

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
-- AUTO_INCREMENT de la tabla `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `purchase_order_details`
--
ALTER TABLE `purchase_order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `recipe_items`
--
ALTER TABLE `recipe_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
