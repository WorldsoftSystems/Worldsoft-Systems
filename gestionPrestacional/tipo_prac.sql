-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-11-2024 a las 15:58:23
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
-- Estructura de tabla para la tabla `tipo_prac`
--

CREATE TABLE `tipo_prac` (
  `id` int(11) NOT NULL,
  `descript` varchar(255) NOT NULL,
  `cod_practica` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tipo_prac`
--

INSERT INTO `tipo_prac` (`id`, `descript`, `cod_practica`) VALUES
(1, 'PRESCRIPCION FARMACOLOGICA Y SEGUIMIENTO DE CONTROL DE TRATAMIENTO', 521001),
(2, 'PSICOTERAPIA INDIVIDUAL (SESIONES DE 30 A 60 MINUTOS)', 520101);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tipo_prac`
--
ALTER TABLE `tipo_prac`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cod_practica` (`cod_practica`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tipo_prac`
--
ALTER TABLE `tipo_prac`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
