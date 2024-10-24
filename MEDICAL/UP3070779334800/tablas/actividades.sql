-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 21-10-2024 a las 23:10:09
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
-- Estructura de tabla para la tabla `actividades`
--

CREATE TABLE `actividades` (
  `id` int(255) NOT NULL,
  `codigo` varchar(255) NOT NULL,
  `descripcion` varchar(255) NOT NULL,
  `modalidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividades`
--

INSERT INTO `actividades` (`id`, `codigo`, `descripcion`, `modalidad`) VALUES
(489, '500101', 'MODULO DE HOSPITAL DE DIA (DIARIO)', 5),
(490, '500101', 'MODULO DE HOSPITAL DE DIA (DIARIO)', 6),
(491, '500102', 'ADICIONAL HOSPITAL DE DIA ESPECIALIZADO EN TCA (DIARIO)', 5),
(492, '500102', 'ADICIONAL HOSPITAL DE DIA ESPECIALIZADO EN TCA (DIARIO)', 6),
(493, '500103', 'ADICIONAL HOSPITAL DE DIA ESPECIALIZADO EN INFANTO JUVENIL (DIARIO)', 5),
(494, '500103', 'ADICIONAL HOSPITAL DE DIA ESPECIALIZADO EN INFANTO JUVENIL (DIARIO)', 6),
(495, '500104', 'ADICIONAL HOSPITAL DE DIA SEGUN PROYECTO INSTITUCIONAL (DIARIO)', 5),
(496, '500104', 'ADICIONAL HOSPITAL DE DIA SEGUN PROYECTO INSTITUCIONAL (DIARIO)', 6),
(497, '500105', 'ADICIONAL HOSPITAL DE DIA JORNADA DOBLE (DIARIO)', 6),
(498, '500090', 'SUSPENSION TEMPORAL DE TRATAMIENTO / MODULO', 5),
(499, '500090', 'SUSPENSION TEMPORAL DE TRATAMIENTO / MODULO', 6),
(500, '501001', 'MODULO DE CENTRO DE DIA (DIARIO)', 10),
(501, '503001', 'MODULO DE VIVIENDA ASISTIDA: BAJO NIVEL DE APOYO (DIARIO)', 4),
(502, '503002', 'MODULO DE VIVIENDA ASISTIDA: MEDIANO NIVEL DE APOYO (DIARIO)', 4),
(503, '503003', 'MODULO DE VIVIENDA ASISTIDA: ALTO NIVEL DE APOYO (DIARIO)', 4),
(504, '503004', 'MODULO DE VIVIENDA ASISTIDA: RESIDENCIA TRANSITORIA (DIARIO)', 4),
(505, '503005', 'ESTRATEGIA DE SOSTEN DOMICILIARIA (PRE-ALTA) (DIARIO)', 4),
(506, '503090', 'SUSPENSION TEMPORAL DE TRATAMIENTO / MODULO', 4),
(507, '503001', 'MODULO DE VIVIENDA ASISTIDA: BAJO NIVEL DE APOYO (DIARIO)', 8),
(508, '503002', 'MODULO DE VIVIENDA ASISTIDA: MEDIANO NIVEL DE APOYO (DIARIO)', 8),
(509, '503003', 'MODULO DE VIVIENDA ASISTIDA: ALTO NIVEL DE APOYO (DIARIO)', 8),
(510, '503004', 'MODULO DE VIVIENDA ASISTIDA: RESIDENCIA TRANSITORIA (DIARIO)', 8),
(511, '503005', 'ESTRATEGIA DE SOSTEN DOMICILIARIA (PRE-ALTA) (DIARIO)', 8),
(512, '503090', 'SUSPENSION TEMPORAL DE TRATAMIENTO / MODULO', 8),
(513, '504001', 'EMERGENCIAS ESPECIALIZADAS EN SALUD MENTAL', 15),
(514, '504002', 'TRASLADO DE EMERGENCIAS DE SALUD MENTAL', 15),
(515, '505000', 'MODULO DE EVALUACION CLINICA INTEGRAL (DIARIO)', 16),
(516, '506001', 'IA-PSICOTERAPIAS INDIVIDUALES PRACTICADAS POR ESPECIALISTAS (CON O SIN PSICODIAGNOSTICO) DEBIDAMENTE AUTORIZADAS', 12),
(517, '506002', 'IA-PSICOTERAPIAS GRUPALES O COLECTIVAS PRACTICADA POR ESPECIALISTAS DEBIDAMENTE AUTORIZADOS', 12),
(518, '506003', 'IA-PSICOTERAPIA DE PAREJA O FAMILIA.', 12),
(519, '506008', 'IA - TRABAJO SOCIAL', 12),
(520, '506009', 'IA-TERAPIA OCUPACIONAL', 12),
(521, '506010', 'IA-MUSICOTERAPIA', 12),
(522, '506011', 'IA-EXPRESION CORPORAL', 12),
(523, '506012', 'IA-ENTREVISTA DE ADMISION Y DIAGNOSTICO', 12),
(524, '506013', 'IA-ENTREVISTA DIAGNOSTICA FAMILIAR', 12),
(525, '506014', 'IA-PSICOTERAPIA VINCULAR', 12),
(526, '506016', 'IA - ORIENTACION A FAMILIA Y/O REFERENTE AFECTIVO', 12),
(527, '506017', 'IA-EXAMEN CLINICO EN INTERNACION PSIQUIATRICA', 12),
(528, '506018', 'IA- CONTROL CLINICO EN INTERNACION PSIQUIATRICA', 12),
(529, '506019', 'IA-CONTROL PSIQUIATRICO', 12),
(530, '506020', 'IA-TALLER LUDICO Y RECREATIVO', 12),
(531, '506021', 'IA-TALLER DE ARTES PLASTICAS', 12),
(532, '506023', 'IA-TALLER LITERARIO', 12),
(533, '506025', 'IA-CONTROL MEDICO CLINICO', 12),
(534, '506026', 'IA-CONTROL DE NUTRICION', 12),
(535, '506027', 'IA-SESION DE FISIOKINESIOTERAPIA', 12),
(536, '506028', 'IA-ANALISIS DE LABORATORIO', 12),
(537, '506029', 'INTERNACION DOMICILIARIA', 12),
(538, '506031', 'ATENCION GUARDIA EXTERNA', 12),
(539, '506032', 'INTERNACION AGUDA EN PISO', 12),
(540, '506033', 'IA - ESTRATEGIA DE PRE -ALTA Y DERIVACION ASISTIDA', 12),
(541, '506034', 'IA - ATENCION A LA DEMANDA ESPONTANEA', 12),
(542, '506035', 'IA - GUARDIA INTERNA', 12),
(543, '506037', 'IA - SERVICIO DE ENFERMERIA', 12),
(544, '506038', 'IA - ACCIONES DE APOYO/INTEGRACION PSICOSOCIAL EN LA RED SOCIOSANITARIA.', 12),
(545, '506039', 'IA - ACCIONES DE PROMOCION Y PREVENCION', 12),
(546, '507001', 'ATENCION A LA CRISIS', 14),
(547, '508001', 'ATENCION A LA CRISIS Y RESOLUCION EN DOMICILIO', 2),
(548, '508002', 'EVALUACION POR OFICIO JUDICIAL QUE SOLICITA INTERNACION INVOLUNTARIA (ART.20 LSM)', 2),
(549, '509001', 'IC-PSICOTERAPIAS INDIVIDUALES PRACTICADAS POR ESPECIALISTAS (CON O SIN PSICODIAGNOSTICO) DEBIDAMENTE AUTORIZADAS.', 11),
(550, '509002', 'IC-PSICOTERAPIAS GRUPALES O COLECTIVAS PRACTICADA POR ESPECIALISTAS DEBIDAMENTE AUTORIZADOS', 11),
(551, '509003', 'IC-PSICOTERAPIA DE PAREJA O FAMILIA.', 11),
(552, '509004', 'IC-ELECTROSHOCK ELECTRONARCOSIS', 11),
(553, '509005', 'IC-SUEÑO PROLONGADO. POR DIA. INCLUYE LAS VISITAS DURANTE ESE PERIODO.', 11),
(554, '509006', 'IC-PRUEBAS PSICOMETRICAS (BATERIA DE UN MINIMO DECUATRO TESTS).', 11),
(555, '509007', 'IC-PRUEBAS PROYECTIVAS. PERFIL DE PERSONALIDAD (BATERIA DE UN MINIMO DE 4 TESTS).', 11),
(556, '509008', 'IC-ASISTENCIA SOCIAL INDIVIDUAL', 11),
(557, '509009', 'IC-TERAPIA OCUPACIONAL', 11),
(558, '509010', 'IC-MUSICOTERAPIA', 11),
(559, '509011', 'IC-EXPRESION CORPORAL', 11),
(560, '509012', 'IC-ENTREVISTA DE ADMISION Y DIAGNOSTICO', 11),
(561, '509013', 'IC-ENTREVISTA DIAGNOSTICA FAMILIAR', 11),
(562, '509014', 'IC-PSICOTERAPIA VINCULAR', 11),
(563, '509015', 'IC-PSICOTERAPIA EN TRASTORNOS DE ALIMENTACION', 11),
(564, '509016', 'IC-ORIENTACION A PADRES', 11),
(565, '509017', 'IC-EXAMEN CLINICO EN INTERNACION PSIQUIATRICA', 11),
(566, '509018', 'IC-CONTROL CLINICO EN INTERNACION PSIQUIATRICA', 11),
(567, '509019', 'IC-CONTROL PSIQUIATRICO', 11),
(568, '509020', 'IC-TALLER LUDICO Y RECREATIVO', 11),
(569, '509021', 'IC-TALLER DE ARTES PLASTICAS', 11),
(570, '509022', 'IC-TERAPIA COGNITIVA CONDUCTUAL', 11),
(571, '509023', 'IC-TALLER LITERARIO', 11),
(572, '509024', 'IC-TALLER DE MEMORIA', 11),
(573, '509025', 'IC-CONTROL MEDICO CLINICO', 11),
(574, '509026', 'IC-CONTROL DE NUTRICION', 11),
(575, '509027', 'IC-SESION DE FISIOKINESIOTERAPIA', 11),
(576, '509028', 'IC-ANALISIS DE LABORATORIO', 11),
(577, '509029', 'INTERNACION PROLONGADA (POR DIA DE INTERNACION)', 11),
(578, '520101', 'PSICOTERAPIA INDIVIDUAL (SESIONES DE 30 A 60 MINUTOS)', 13),
(579, '521001', 'PRESCRIPCION FARMACOLOGICA Y SEGUIMIENTO DE CONTROL DE TRATAMIENTO', 13),
(580, '522001', 'PROMOCION Y PREVENCION: TALLER GRUPAL - GRUPO DE REFLEXION', 4),
(581, '522002', 'PROMOCION Y PREVENCION: TALLER GRUPAL -', 4),
(582, '522003', 'PROMOCION Y PREVENCION: TALLER GRUPAL - APOYO PSICOSOCIAL', 4),
(583, '522004', 'PROMOCION Y PREVENCION: CONSULTA DE ORIENTACION', 4),
(584, '522005', 'PROMOCION Y PREVENCION: CONSEJERIA / ORIENTACION EN SALUD MENTAL, ILE, VIOLENCIA DE GENERO', 4),
(585, '522006', 'PROMOCION Y PREVENCION: CONSEJERIA/ORIENTACION EN SALUD MENTAL GRUPAL / FAMILIAR', 4),
(586, '522007', 'PROMOCION Y PREVENCION: APOYO PSICOSOCIAL PARA PERSONAS AFECTADAS POR INCIDENTES CRITICOS', 4),
(587, '522008', 'PRACTICAS COMUNITARIAS: ACTIVIDADES GRUPALES DEPORTIVAS, CULTURALES COMUNITARIAS (MENSUAL)', 4),
(588, '522009', 'PRACTICAS COMUNITARIAS: TALLERES PREVENTIVOS EN SALUD MENTAL (MENSUAL)', 4),
(589, '522010', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: ACOMPAÑAMIENTO TERAPEUTICO', 4),
(590, '522011', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: ATENCION DOMICILIARIA DEL EMBARAZO Y PUERPERIO', 4),
(591, '522012', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: REACONDICIONAMIENTO Y ADECUACION DEL ESPACIO HABITACIONAL', 4),
(592, '522013', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: SOSTEN DEL VINCULO', 4),
(593, '522014', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: DERIVACION ASISTIDA', 4),
(594, '522015', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: TRABAJO SOCIAL TERRITORIAL', 4),
(595, '522016', 'CONSULTORIOS EXTERNOS: ENTREVISTA DE ADMISION Y DERIVACION (INCLUYE TEST PSICOMETRICOS PROYECTIVOS)', 4),
(596, '522017', 'CONSULTORIOS EXTERNOS: PSICOTERAPIAS GRUPALES PRACTICADAS POR ESPECIALISTAS', 4),
(597, '522018', 'CONSULTORIOS EXTERNOS: PSICOTERAPIA VINCULAR (INCLUYE REFERENTE AFECTIVO)', 4),
(598, '522019', 'CONSULTORIOS EXTERNOS: CONSULTA CON TRABAJO SOCIAL', 4),
(599, '522020', 'CONSULTORIOS EXTERNOS: TERAPIA OCUPACIONAL', 4),
(600, '522021', 'CONSULTORIOS EXTERNOS: MUSICOTERAPIA', 4),
(601, '522022', 'CONSULTORIOS EXTERNOS: CONSULTA ESPECIALISTA PSIQUIATRICA INSTITUCIONAL', 4),
(602, '522023', 'CONSULTORIOS EXTERNOS: CONSULTA ESPECIALISTA PSICOPEDAGOGIA', 4),
(603, '522024', 'CONSULTORIOS EXTERNOS: ENTREVISTA DIAGNOSTICA FAMILIAR', 4),
(604, '522025', 'CONSULTORIOS EXTERNOS: TELECONSULTA DE ADMISION EN SALUD MENTAL Y SEGUIMIENTO', 4),
(605, '522026', 'CONSULTORIOS EXTERNOS: CONSULTA PSICOLOGICA INSTITUCIONAL', 4),
(606, '522027', 'CONSULTORIOS EXTERNOS: DETERMINACION DE LA CAPACIDAD', 4),
(607, '522028', 'CONSULTORIOS EXTERNOS: ESTRATEGIA DE ALTA FRECUENCIA', 4),
(608, '522001', 'PROMOCION Y PREVENCION: TALLER GRUPAL - GRUPO DE REFLEXION', 7),
(609, '522002', 'PROMOCION Y PREVENCION: TALLER GRUPAL -', 7),
(610, '522003', 'PROMOCION Y PREVENCION: TALLER GRUPAL - APOYO PSICOSOCIAL', 7),
(611, '522004', 'PROMOCION Y PREVENCION: CONSULTA DE ORIENTACION', 7),
(612, '522005', 'PROMOCION Y PREVENCION: CONSEJERIA / ORIENTACION EN SALUD MENTAL, ILE, VIOLENCIA DE GENERO', 7),
(613, '522006', 'PROMOCION Y PREVENCION: CONSEJERIA/ORIENTACION EN SALUD MENTAL GRUPAL / FAMILIAR', 7),
(614, '522007', 'PROMOCION Y PREVENCION: APOYO PSICOSOCIAL PARA PERSONAS AFECTADAS POR INCIDENTES CRITICOS', 7),
(615, '522008', 'PRACTICAS COMUNITARIAS: ACTIVIDADES GRUPALES DEPORTIVAS, CULTURALES COMUNITARIAS (MENSUAL)', 7),
(616, '522009', 'PRACTICAS COMUNITARIAS: TALLERES PREVENTIVOS EN SALUD MENTAL (MENSUAL)', 7),
(617, '522010', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: ACOMPAÑAMIENTO TERAPEUTICO', 7),
(618, '522011', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: ATENCION DOMICILIARIA DEL EMBARAZO Y PUERPERIO', 7),
(619, '522012', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: REACONDICIONAMIENTO Y ADECUACION DEL ESPACIO HABITACIONAL', 7),
(620, '522013', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: SOSTEN DEL VINCULO', 7),
(621, '522014', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: DERIVACION ASISTIDA', 7),
(622, '522015', 'PRACTICAS COMUNITARIAS DE REHABILITACION PSICOSOCIAL: TRABAJO SOCIAL TERRITORIAL', 7),
(623, '522016', 'CONSULTORIOS EXTERNOS: ENTREVISTA DE ADMISION Y DERIVACION (INCLUYE TEST PSICOMETRICOS PROYECTIVOS)', 7),
(624, '522017', 'CONSULTORIOS EXTERNOS: PSICOTERAPIAS GRUPALES PRACTICADAS POR ESPECIALISTAS', 7),
(625, '522018', 'CONSULTORIOS EXTERNOS: PSICOTERAPIA VINCULAR (INCLUYE REFERENTE AFECTIVO)', 7),
(626, '522019', 'CONSULTORIOS EXTERNOS: CONSULTA CON TRABAJO SOCIAL', 7),
(627, '522020', 'CONSULTORIOS EXTERNOS: TERAPIA OCUPACIONAL', 7),
(628, '522021', 'CONSULTORIOS EXTERNOS: MUSICOTERAPIA', 7),
(629, '522022', 'CONSULTORIOS EXTERNOS: CONSULTA ESPECIALISTA PSIQUIATRICA INSTITUCIONAL', 7),
(630, '522023', 'CONSULTORIOS EXTERNOS: CONSULTA ESPECIALISTA PSICOPEDAGOGIA', 7),
(631, '522024', 'CONSULTORIOS EXTERNOS: ENTREVISTA DIAGNOSTICA FAMILIAR', 7),
(632, '522025', 'CONSULTORIOS EXTERNOS: TELECONSULTA DE ADMISION EN SALUD MENTAL Y SEGUIMIENTO', 7),
(633, '522026', 'CONSULTORIOS EXTERNOS: CONSULTA PSICOLOGICA INSTITUCIONAL', 7),
(634, '522027', 'CONSULTORIOS EXTERNOS: DETERMINACION DE LA CAPACIDAD', 7),
(635, '522028', 'CONSULTORIOS EXTERNOS: ESTRATEGIA DE ALTA FRECUENCIA', 7),
(636, '522029', 'ATENCION PROGRAMADA EN DOMICILIO: ENTREVISTA DE DIAGNOSTICO Y PLANIFICACION TERAPEUTICA. DETERMINACION DE LA CAPACIDAD', 1),
(637, '522030', 'ATENCION PROGRAMADA EN DOMICILIO: CONSULTA PSIQUIATRIA', 1),
(638, '522031', 'ATENCION PROGRAMADA EN DOMICILIO: CONSULTA DE PSICOLOGIA', 1),
(639, '522032', 'ATENCION PROGRAMADA EN DOMICILIO: TRABAJO SOCIAL', 1),
(640, '522033', 'ATENCION PROGRAMADA EN DOMICILIO: TERAPISTA OCUPACIONAL', 1);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_actividad_modalidad` (`modalidad`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividades`
--
ALTER TABLE `actividades`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=641;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividades`
--
ALTER TABLE `actividades`
  ADD CONSTRAINT `fk_actividad_modalidad` FOREIGN KEY (`modalidad`) REFERENCES `modalidad` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
