-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-10-2024 a las 22:54:47
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
-- Base de datos: `medical_pq000`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `practicas`
--

CREATE TABLE `practicas` (
  `id_paciente` int(255) NOT NULL,
  `fecha` date NOT NULL,
  `hora` time DEFAULT NULL,
  `profesional` int(255) NOT NULL,
  `actividad` int(255) NOT NULL,
  `cant` int(255) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Disparadores `practicas`
--
DELIMITER $$
CREATE TRIGGER `before_insert_practicas` BEFORE INSERT ON `practicas` FOR EACH ROW BEGIN
    DECLARE actividad_id INT;

    -- Buscar el ID de la actividad basado en el código
    SELECT id INTO actividad_id 
    FROM actividades 
    WHERE codigo = NEW.actividad;

    -- Si se encuentra el id correspondiente, asignarlo a la columna 'actividad'
    IF actividad_id IS NOT NULL THEN
        SET NEW.actividad = actividad_id;
    ELSE
        -- Si no se encuentra el código, lanzar un error
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'El código de actividad no es válido';
    END IF;
END
$$
DELIMITER ;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `practicas`
--
ALTER TABLE `practicas`
  ADD PRIMARY KEY (`id`,`id_paciente`),
  ADD KEY `profesiona_fk` (`profesional`),
  ADD KEY `actividad_fk` (`actividad`),
  ADD KEY `fk_id_paciente_practica` (`id_paciente`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `practicas`
--
ALTER TABLE `practicas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `practicas`
--
ALTER TABLE `practicas`
  ADD CONSTRAINT `actividad_fk` FOREIGN KEY (`actividad`) REFERENCES `actividades` (`id`),
  ADD CONSTRAINT `fk_id_paciente_practica` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id`),
  ADD CONSTRAINT `profesiona_fk` FOREIGN KEY (`profesional`) REFERENCES `profesional` (`id_prof`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
