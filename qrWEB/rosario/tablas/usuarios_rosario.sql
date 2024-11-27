-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-09-2024 a las 19:06:17
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
-- Base de datos: `qr`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_rosario`
--

CREATE TABLE `usuarios_rosario` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cant_qr` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios_rosario`
--

INSERT INTO `usuarios_rosario` (`id`, `user`, `password`, `cant_qr`) VALUES
(1, 'admin', 'wss1593', 458),
(2, 'UP3061517356400', 'qrfacil24', 906),
(3, 'UP3061426197400', 'qrfacil24', 602),
(4, 'UP3056162862500', 'qrfacil24', 639),
(5, 'UP3054608885100', 'qrfacil24', 583),
(6, 'UP3067447158700', 'qrfacil24', 2253),
(7, 'UP3061311446301', 'qrfacil24', 614),
(8, 'UP3058481563500', 'qrfacil24', 1017),
(9, 'UP30670538903', 'qrfacil24', 1286),
(10, 'UP3054585184503', 'qrfacil24', 0),
(11, 'UP30714474517', 'qrfacil24', 0),
(12, 'UP3069592429800', 'qrfacil24', 247),
(13, 'UP3061492697600', 'qrfacil24', 1834),
(14, 'UP3063207857500', 'qrfacil2024', 369),
(15, 'UP3070711012700', 'qrfacil24', 45);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios_rosario`
--
ALTER TABLE `usuarios_rosario`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios_rosario`
--
ALTER TABLE `usuarios_rosario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
