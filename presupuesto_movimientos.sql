-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2026 a las 04:56:34
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
-- Estructura de tabla para la tabla `presupuesto_movimientos`
--

CREATE TABLE `presupuesto_movimientos` (
  `id_movimiento` int(11) NOT NULL,
  `asignacion_id` int(11) NOT NULL COMMENT 'De qué bolsa (Centro/Ítem) sale el dinero',
  `tercero_id` int(11) NOT NULL COMMENT 'A quién se le paga',
  `tipo_movimiento` enum('Pago','Compra','Nomina','Servicio','Otro') NOT NULL,
  `fecha` date NOT NULL,
  `numero_documento` varchar(50) DEFAULT NULL COMMENT 'Nro Factura, Cuenta de Cobro',
  `detalle` text NOT NULL COMMENT 'Observación detallada del gasto',
  `valor` decimal(15,2) NOT NULL,
  `soporte_url` varchar(255) DEFAULT NULL COMMENT 'Ruta al archivo PDF/Img',
  `usuario_id` int(11) NOT NULL COMMENT 'Quién registró',
  `datecreated` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `presupuesto_movimientos`
--
ALTER TABLE `presupuesto_movimientos`
  ADD PRIMARY KEY (`id_movimiento`),
  ADD KEY `asignacion_id` (`asignacion_id`),
  ADD KEY `tercero_id` (`tercero_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `presupuesto_movimientos`
--
ALTER TABLE `presupuesto_movimientos`
  MODIFY `id_movimiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `presupuesto_movimientos`
--
ALTER TABLE `presupuesto_movimientos`
  ADD CONSTRAINT `fk_mov_asignacion` FOREIGN KEY (`asignacion_id`) REFERENCES `presupuesto_asignacion` (`id_asignacion`),
  ADD CONSTRAINT `fk_mov_tercero` FOREIGN KEY (`tercero_id`) REFERENCES `terceros` (`id_tercero`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
