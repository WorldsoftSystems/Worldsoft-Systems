-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-09-2024 a las 19:06:37
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
-- Estructura de tabla para la tabla `usuarios_wss`
--

CREATE TABLE `usuarios_wss` (
  `id` int(11) NOT NULL,
  `user` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `cant_qr` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Volcado de datos para la tabla `usuarios_wss`
--

INSERT INTO `usuarios_wss` (`id`, `user`, `password`, `cant_qr`) VALUES
(1, 'admin', 'wss1593', 458),
(2, 'UP3070779334800', 'wsspq0328', 0),
(3, 'UP3060580735200', 'wsspq0311', 551),
(4, 'UP3054619148200', 'wsspq0209', 69),
(5, 'UP3065906851200', 'wsspq0305', 561),
(6, 'UP3065453210500', 'wsspq0211', 256),
(7, 'UP3054619100803', 'wsspq0313', 380),
(8, 'UP3069592429800', 'wsspq0314', 247),
(9, 'UP3061200292000', 'wsspq1501', 1081),
(10, 'UP3057068688301', 'wsspq0239', 451);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios_wss`
--
ALTER TABLE `usuarios_wss`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios_wss`
--
ALTER TABLE `usuarios_wss`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
