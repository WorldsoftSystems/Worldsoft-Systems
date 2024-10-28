-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-10-2024 a las 18:58:42
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
-- Base de datos: `medical_prueba`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `medicacion_paci`
--

CREATE TABLE `medicacion_paci` (
  `id` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `medicamento` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `dosis` double(10,1) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `valor` double(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `medicacion_paci`
--
--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `medicacion_paci`
--
ALTER TABLE `medicacion_paci`
  ADD PRIMARY KEY (`id`,`id_paciente`),
  ADD KEY `fk_medicamento` (`medicamento`),
  ADD KEY `fk_medicamento_paciente` (`id_paciente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `medicacion_paci`
--
ALTER TABLE `medicacion_paci`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `medicacion_paci`
--
ALTER TABLE `medicacion_paci`
  ADD CONSTRAINT `fk_medicamento` FOREIGN KEY (`medicamento`) REFERENCES `medicacion` (`id`),
  ADD CONSTRAINT `fk_medicamento_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
