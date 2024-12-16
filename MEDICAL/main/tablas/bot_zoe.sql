-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 30-10-2024 a las 13:20:35
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
-- Estructura de tabla para la tabla `bot_zoe`
--

CREATE TABLE `bot_zoe` (
  `id` int(11) NOT NULL,
  `pregunta` varchar(255) NOT NULL,
  `respuesta` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `bot_zoe`
--

INSERT INTO `bot_zoe` (`id`, `pregunta`, `respuesta`) VALUES
(1, '¿Hasta cuando tengo tiempo de presentar las estadisticas de pami?', 'Hasta el dia 15  de cada mes.\r\n'),
(2, '¿Cual es el valor de la unidad retributiva?\r\n', 'A partir del mes de agosto de 2024 tiene un incremento de un 2,5% y el valor es $8152.11 y para zona patagonica $ 9782.21. Para el mes de septiembre tiene un incremento de un     24 2% y el valor es $ 8315.15 y para zona pataginica $ 9977.85.'),
(3, '¿Cual es el valor que paga por OME?', 'El valor OME para las prestaciones de psiquiatria (521001) y para psicologia (520101) es el siguiente: Para agosto 2024 $ 7092.34 y para zona patagonica $ 8510.52\r\npara el mes de septiembre 2024 $7234.18 y para zona pataginica $8680.73.\r\n'),
(4, '¿Cuáles son los módulos de Pami de salud mental vigentes?', 'Modulo 496 Hospital de Dia Convinado,\r\nModulo 499 Hospital de Dia especializado en consumo problematico,\r\nModulo 500 Hospital de dia,\r\nModulo 501 Centro de dia,\r\nModulo 502 Emprendimientos Laborales,\r\nModulo 522 Consultorios externos,\r\nModulo 508 Urgencias domiciliarias,\r\nModulo 506 Internación aguda,\r\nModulo 509 Internación prolongada,\r\nModulo 520 Ome para psicología,\r\nModulo 521 Ome para psiquiatria,\r\nModulo 503 Vivienda asistida,\r\nModulo 523 Residencia transitoria.'),
(5, '¿Desde cuándo es válida una orden de prestación(op) que se solicita a Pami?', 'Las ordenes de prestación son válidas desde que se activan y desde ese\r\nmomento se empiezan a contar los días para su vencimiento, en el caso que se soliciten\r\npor 6 meses desde la fecha de activación se le suman 180 días para su vencimiento.'),
(6, '¿Cuáles son las prestaciones de libre elección para el afiliado?', 'Las prestaciones de libre elección para atenciones ambulatorias son \r\nlos módulos 520 y 521 en estos casos la admisión debe ser realizada por el prestador\r\nde origen.'),
(7, '¿Cuál es el tiempo máximo para validar una OME?', 'El tiempo máximo son de 7 días desde el momento de la aceptación');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `bot_zoe`
--
ALTER TABLE `bot_zoe`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `bot_zoe`
--
ALTER TABLE `bot_zoe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
