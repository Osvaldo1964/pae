-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-02-2026 a las 04:56:40
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
-- Estructura de tabla para la tabla `presupuesto_traslados`
--

CREATE TABLE `presupuesto_traslados` (
  `id_traslado` bigint(20) NOT NULL,
  `fecha` date NOT NULL,
  `origen_id` int(11) NOT NULL,
  `destino_id` int(11) NOT NULL,
  `valor` decimal(15,2) NOT NULL,
  `justificacion` text NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `presupuesto_traslados`
--
ALTER TABLE `presupuesto_traslados`
  ADD PRIMARY KEY (`id_traslado`),
  ADD KEY `origen_id` (`origen_id`),
  ADD KEY `destino_id` (`destino_id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `presupuesto_traslados`
--
ALTER TABLE `presupuesto_traslados`
  MODIFY `id_traslado` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `presupuesto_traslados`
--
ALTER TABLE `presupuesto_traslados`
  ADD CONSTRAINT `fk_traslado_destino` FOREIGN KEY (`destino_id`) REFERENCES `presupuesto_asignacion` (`id_asignacion`),
  ADD CONSTRAINT `fk_traslado_origen` FOREIGN KEY (`origen_id`) REFERENCES `presupuesto_asignacion` (`id_asignacion`),
  ADD CONSTRAINT `fk_traslado_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
