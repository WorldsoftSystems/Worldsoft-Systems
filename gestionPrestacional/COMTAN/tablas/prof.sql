-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-11-2024 a las 15:00:44
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
-- Base de datos: `worldsof_gestion_prestacional_comtan`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prof`
--

CREATE TABLE `prof` (
  `cod_prof` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `especialidad` varchar(255) NOT NULL,
  `prof_generador` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prof`
--

INSERT INTO `prof` (`cod_prof`, `nombre`, `apellido`, `especialidad`, `prof_generador`) VALUES
(48, 'ANRES', 'BALLENT', 'psiquiatria', NULL),
(49, 'CELESTE', 'BEHOTAS', 'psiquiatria', NULL),
(50, 'MARIA CLAUDIA', 'CASTRO', 'psiquiatria', NULL),
(51, 'AGUSTIN', 'FORBITO', 'psiquiatria', NULL),
(53, 'LISANDRO ESTEBAN', 'GILLIGAN', 'psiquiatria', NULL),
(54, 'ROMINA', 'SILVA', 'psiquiatria', NULL),
(55, 'SILVINA', 'OLIVERA', 'psiquiatria', NULL),
(56, 'CARLOS EDUADO', 'PEREZ', 'psiquiatria', NULL),
(58, 'FRANCO', 'MILESI', 'psicologia', NULL),
(59, 'LUCIA', 'HANSEN', 'psicologia', NULL),
(60, 'JORGELINA', 'MICHIA', 'psicologia', NULL),
(61, 'CLAUDIA', 'GHEZZI', 'psicologia', NULL),
(62, 'MICAELA', 'BUJOSA', 'psicologia', NULL),
(63, 'MARIA LAURA', 'GIANGIOBBE', 'psicologia', NULL),
(64, 'PAULA', 'ROMERO', 'psicologia', NULL),
(65, 'VANESA', 'MARTINEZ', 'psicologia', NULL),
(66, 'PRICILA', 'CLERICI ERHARDT', 'psicologia', NULL),
(67, 'SILVINA', 'PRESA', 'psicologia', NULL),
(68, 'ROCIO', 'SAENZ', 'psicologia', NULL),
(69, 'JOHANA MARISOL', 'MURILLO', 'psicologia', NULL),
(70, 'NANCY EDITH', 'FERNANDEZ', 'psicologia', NULL),
(71, 'EMILIANO JAVIER', 'BUDRONI', 'psicologia', NULL),
(72, 'JESSICA', 'GALOTTI', 'psicologia', NULL),
(73, 'MARTIN', 'LARDAPIDE', 'psiquiatria', NULL),
(74, 'VERONICA', 'CERONO', 'psicologia', NULL),
(75, 'MARIA CELINA ', 'GIANNOTTI ', 'psicologia', NULL),
(76, 'MARIA FLORENCIA', 'RISSO', 'psicologia', NULL),
(77, 'a', 'a', 'psicologia', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `prof`
--
ALTER TABLE `prof`
  ADD PRIMARY KEY (`cod_prof`),
  ADD KEY `fk_generador_pf` (`prof_generador`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `prof`
--
ALTER TABLE `prof`
  MODIFY `cod_prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `prof`
--
ALTER TABLE `prof`
  ADD CONSTRAINT `fk_generador_pf` FOREIGN KEY (`prof_generador`) REFERENCES `prof` (`cod_prof`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
