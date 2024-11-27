-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 22-09-2024 a las 20:09:24
-- Versión del servidor: 10.6.19-MariaDB-cll-lve-log
-- Versión de PHP: 8.3.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `worldsof_OME`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prof`
--

CREATE TABLE `prof` (
  `cod_prof` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `apellido` varchar(255) NOT NULL,
  `especialidad` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `prof`
--

INSERT INTO `prof` (`cod_prof`, `nombre`, `apellido`, `especialidad`) VALUES
(54, 'Doris ', 'Isidori - ALLEN', 'psicologia'),
(55, 'Maria Veronica', 'Hernalz - CHOELE CHOEL', 'psiquiatria'),
(56, 'Mylton ', 'Mercado', 'psiquiatria'),
(57, 'Adriana ', 'Salamanca - CIPOLLETTI', 'psicologia'),
(58, 'Maria ', 'Valenzuela - CIPOLLETTI', 'psicologia'),
(59, 'Cintia', 'Apezetche - CIPOLLETTI', 'psicologia'),
(60, 'Veronica ', 'Nunez - EL BOLSON', 'psicologia'),
(61, 'Cecilia', 'Oriol - EL BOLSON', 'psicologia'),
(62, 'Alejo ', 'Fowler', 'psiquiatria'),
(63, 'Azul Luna', 'Genovessi Stoffel - GRAL. ROCA', 'psicologia'),
(64, 'Mercedes Jael', 'Ramirez - GRAL. ROCA', 'psicologia'),
(65, 'Maria', 'Rodriguez - GRAL. ROCA', 'psicologia'),
(66, 'Belen', 'Ponce - GRAL. ROCA', 'psicologia'),
(68, 'Adrian ', 'Volta - LAS GRUTAS - SAN ANTONIO OESTE', 'psicologia'),
(69, 'Romina ', 'Ricco - LAS GRUTAS - SAN ANTONIO OESTE', 'psicologia'),
(71, 'Manuel ', 'Romero - CONESA', 'psicologia'),
(72, 'Axel Oscar ', 'Ganza Perez', 'psiquiatria'),
(73, 'Laura', 'Bortolin ', 'psiquiatria'),
(74, 'Mariana', 'Patelepen ', 'psiquiatria'),
(75, 'Alejandro ', 'Izaguirre - SAN CARLOS DE BARILOCHE', 'psicologia'),
(76, 'Alejandra ', 'Martinez - SAN CARLOS DE BARILOCHE', 'psicologia'),
(77, 'Marcela ', 'Celano - SAN CARLOS DE BARILOCHE', 'psicologia'),
(79, 'Virginia', 'Scarpelli - SAN CARLOS DE BARILOCHE', 'psicologia'),
(80, 'Valeria ', 'Acevedo - SAN CARLOS DE BARILOCHE', 'psicologia'),
(81, 'Manuel', 'Montero', 'psiquiatria'),
(82, 'Patricia', 'Garcia - VIEDMA', 'psicologia'),
(83, 'Jesica Laura ', 'Vecino - VIEDMA', 'psicologia'),
(84, 'Brenda ', 'Velozo  - VILLA REGINA', 'psicologia'),
(85, 'Maria ', 'Vesprini - VILLA REGINA', 'psicologia'),
(88, 'Liliana Mabel', 'Saracco  - SAN CARLOS DE BARILOCHE', 'psicologia'),
(89, '..', '..', 'psicologia'),
(90, 'Lilian Marianela', 'Fernandez - VILLA REGINA', 'psicologia'),
(91, 'WALTER', 'ROSENDO', 'psiquiatria'),
(92, 'Ornella', 'Vellico', 'psicologia'),
(93, 'MONICA GRACIELA', 'LANGUNI', 'psicologia'),
(94, 'Ivana', 'Del Valle Gimenez ', 'psiquiatria'),
(95, 'JULIETA MACARENA', 'VENDIGNI', 'psicologia'),
(96, 'Manuel', 'Romero', 'psicologia');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `prof`
--
ALTER TABLE `prof`
  ADD PRIMARY KEY (`cod_prof`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `prof`
--
ALTER TABLE `prof`
  MODIFY `cod_prof` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=97;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
