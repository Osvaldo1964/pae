-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2026 a las 04:56:05
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
-- Base de datos: `db_siam`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `presupuesto_items`
--

CREATE TABLE `presupuesto_items` (
  `id_item` int(11) NOT NULL,
  `codigo` varchar(20) NOT NULL COMMENT 'Ej: 1.1, 2.3.1',
  `nombre` text NOT NULL,
  `descripcion` text DEFAULT NULL,
  `padre_id` int(11) DEFAULT NULL COMMENT 'ID del ítem padre (para jerarquía)',
  `unidad_medida` varchar(50) DEFAULT NULL COMMENT 'Ej: Meses, Global, Unidades',
  `cantidad_global` decimal(12,3) DEFAULT 0.000,
  `tiempo_global` decimal(12,3) NOT NULL DEFAULT 0.000,
  `valor_unitario_oficial` decimal(15,2) DEFAULT 0.00,
  `valor_total_oficial` decimal(15,2) DEFAULT 0.00 COMMENT 'Valor total del proyecto por este ítem',
  `estado` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `presupuesto_items`
--

INSERT INTO `presupuesto_items` (`id_item`, `codigo`, `nombre`, `descripcion`, `padre_id`, `unidad_medida`, `cantidad_global`, `tiempo_global`, `valor_unitario_oficial`, `valor_total_oficial`, `estado`) VALUES
(1, '1.0', 'AUXILIO CENTROS DE ATENCION', 'Auxilio en los costos de Atención de las personas mayores', NULL, '', 1.000, 0.000, 0.00, 0.00, 1),
(2, '1.1', 'AUXILIO CENTROS DE ATENCION', 'Auxilios para la operación de Centros de Atención', 1, '', 300.000, 2.000, 1425000.00, 855000000.00, 1),
(3, '1.2', 'prueba', 'sdsdssd', 1, '', 5.000, 2.000, 25000.00, 250000.00, 0),
(4, '2.0', 'ALIMENTACION POR MINUTAS', 'SUMINISTRO DE ALIMENTACION A ADULTOS MAYORES', NULL, '', 1.000, 1.000, 0.00, 0.00, 1),
(5, '2.1', 'NUTRICIONISTA', 'NUTRICIONISTA DE APOYO A TODOS LOS CENTROS DE ASISTENCIA', 4, '', 1.000, 2.000, 5700000.00, 11400000.00, 1),
(6, '2.2', 'SUMINISTRO DESAYUNOS', 'Raciones (Hiposódica/Hipoglicida/Astringente)', 4, '', 2520.000, 2.000, 15900.00, 80136000.00, 1),
(7, '2.3', 'SUMINISTRO DESAYUNOS', 'Raciones (Normal Alta en Fibra)', 4, '', 6480.000, 2.000, 15450.00, 200232000.00, 1),
(8, '2.4', 'Suministro de alimentación Media Mañana', 'Raciones (Normal Alta en Fibra)', 4, 'Mes', 9000.000, 2.000, 13050.00, 234900000.00, 1),
(9, '2.5', 'Suministro de alimentación Almuerzo', 'Raciones (Hiposódica/Hipoglicida/Astringente)', 4, 'Mes', 2520.000, 2.000, 29700.00, 149688000.00, 1),
(10, '2.6', 'Suministro de alimentación Almuerzo', 'Raciones (Normal Alta en Fibra)', 4, 'Mes', 6480.000, 2.000, 30450.00, 394632000.00, 1),
(11, '2.7', 'Suministro de alimentación Cena', 'Raciones (Hiposódica/Hipoglicida/Astringente)', 4, 'Mes', 2520.000, 2.000, 15900.00, 80136000.00, 1),
(12, '2.8', 'Suministro de alimentación Cena', 'Raciones (Normal Alta en Fibra)', 4, 'Mes', 6480.000, 2.000, 15450.00, 200232000.00, 1),
(13, '2.9', 'SUPLEMENTOS Ensure advance', 'SUPLEMENTOS Ensure advance de 850g EN POLVO (1,5 potes por cada adulto mayor)', 4, 'Mes', 450.000, 2.000, 225000.00, 202500000.00, 1),
(14, '3.0', 'INSUMOS DE ASEO Y LIMPIEZA', 'Suministro insumo de aseo y limpieza institucional (2 detergente x 4 kilogramos, 4 paquetes de papel higiénico por 12 rollos, 8 Hipoclorito de Sodio por 3000 ml, 6 Lavaloza por 500 gr, 2 Ambientadores por 1000 gr, 4 esponjillas lava vajillas, 8 limpia piso por 4000 ml )', NULL, '', 1.000, 1.000, 0.00, 0.00, 1),
(15, '3.1', 'SUMINISTRO INSUMOS ASEO', 'Suministro insumo de aseo y limpieza institucional (2 detergente x 4 kilogramos, 4 paquetes de papel higiénico por 12 rollos, 8 Hipoclorito de Sodio por 3000 ml, 6 Lavaloza por 500 gr, 2 Ambientadores por 1000 gr, 4 esponjillas lava vajillas, 8 limpia piso por 4000 ml )', 14, 'Glb', 6.000, 2.000, 723000.00, 8676000.00, 1),
(16, '3.2', 'FUMIGACION', 'Realizar fumigación de cada uno de los centros de bienestar', 14, 'Glb', 6.000, 1.000, 1000000.00, 6000000.00, 1),
(17, '4.0', 'DOTACION PERSONAL', '', NULL, '', 1.000, 1.000, 0.00, 0.00, 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `presupuesto_items`
--
ALTER TABLE `presupuesto_items`
  ADD PRIMARY KEY (`id_item`),
  ADD KEY `padre_id` (`padre_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `presupuesto_items`
--
ALTER TABLE `presupuesto_items`
  MODIFY `id_item` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `presupuesto_items`
--
ALTER TABLE `presupuesto_items`
  ADD CONSTRAINT `fk_item_padre` FOREIGN KEY (`padre_id`) REFERENCES `presupuesto_items` (`id_item`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
