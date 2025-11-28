-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 28-11-2025 a las 08:41:52
-- Versión del servidor: 10.4.22-MariaDB
-- Versión de PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `php_login_database`
--
CREATE DATABASE IF NOT EXISTS `php_login_database` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE `php_login_database`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `comunidades`
--

DROP TABLE IF EXISTS `comunidades`;
CREATE TABLE IF NOT EXISTS `comunidades` (
  `id_comunidad` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_comunidad` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `id_municipio` int(11) NOT NULL,
  PRIMARY KEY (`id_comunidad`),
  UNIQUE KEY `nombre_comunidad` (`nombre_comunidad`),
  KEY `id_municipio` (`id_municipio`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `comunidades`
--

INSERT INTO `comunidades` (`id_comunidad`, `nombre_comunidad`, `id_municipio`) VALUES
(1, 'Cashama', 1),
(2, 'Tascabaña I', 1),
(3, 'Tascabaña II', 1),
(4, 'Mare Mare', 1),
(5, 'Bajohondo', 1),
(6, 'Bajohondo (Mangalito)', 1),
(7, 'Barbonero', 1),
(8, 'Mapiricure', 1),
(9, 'Las Potocas', 1),
(10, 'La Florida', 1),
(11, 'Santa Rosa de la Magnolia', 1),
(12, 'Paramán', 1),
(13, 'La Isla', 1),
(14, 'La Mata', 1),
(15, 'La Leona', 1),
(16, 'San Vicente de Tacata', 1),
(17, 'Mapiricurito', 1),
(18, 'Chimire', 1),
(19, 'La Ceibita', 1),
(20, 'Mara', 1),
(27, 'La noche', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados`
--

DROP TABLE IF EXISTS `estados`;
CREATE TABLE IF NOT EXISTS `estados` (
  `id_estados` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_estado` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_estados`),
  UNIQUE KEY `nombre_estado` (`nombre_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estados`
--

INSERT INTO `estados` (`id_estados`, `nombre_estado`) VALUES
(1, 'Amazonas'),
(2, 'Anzoátegui'),
(3, 'Apure'),
(4, 'Aragua'),
(5, 'Barinas'),
(6, 'Bolívar'),
(7, 'Carabobo'),
(8, 'Cojedes'),
(9, 'Delta Amacuro'),
(10, 'Falcón'),
(11, 'Guárico'),
(21, 'La Guaira'),
(12, 'Lara'),
(13, 'Mérida'),
(14, 'Miranda'),
(15, 'Monagas'),
(16, 'Nueva Esparta'),
(17, 'Portuguesa'),
(18, 'Sucre'),
(19, 'Táchira'),
(20, 'Trujillo'),
(22, 'Yaracuy'),
(23, 'Zulia');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiante`
--

DROP TABLE IF EXISTS `estudiante`;
CREATE TABLE IF NOT EXISTS `estudiante` (
  `persona_id` int(11) NOT NULL,
  `ano_grado` enum('1','2','3','4','5','6') COLLATE utf8_unicode_ci NOT NULL,
  `fecha_inscripcion` date NOT NULL,
  `periodo_escolar_id` int(11) NOT NULL,
  `estatus` enum('Inscrito','Expulsado','Retirado','Graduado') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persona_id`),
  KEY `periodo_escolar_id` (`periodo_escolar_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `estudiante`
--

INSERT INTO `estudiante` (`persona_id`, `ano_grado`, `fecha_inscripcion`, `periodo_escolar_id`, `estatus`) VALUES
(191, '3', '2025-08-23', 11, 'Inscrito'),
(212, '3', '2025-08-29', 11, 'Inscrito'),
(254, '1', '2025-09-28', 11, 'Expulsado'),
(267, '3', '2025-10-16', 11, 'Inscrito'),
(268, '3', '2025-10-16', 11, 'Inscrito'),
(269, '3', '2025-10-16', 11, 'Inscrito'),
(270, '3', '2025-10-16', 11, 'Inscrito'),
(271, '3', '2025-10-16', 11, 'Inscrito'),
(272, '3', '2025-10-16', 11, 'Inscrito'),
(273, '3', '2025-10-16', 11, 'Inscrito'),
(274, '3', '2025-10-16', 11, 'Inscrito'),
(275, '3', '2025-10-16', 11, 'Inscrito'),
(276, '3', '2025-10-16', 11, 'Inscrito'),
(287, '3', '2025-11-21', 11, 'Inscrito'),
(294, '3', '2025-11-26', 13, 'Inscrito'),
(295, '3', '2025-11-26', 13, 'Inscrito');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `materias`
--

DROP TABLE IF EXISTS `materias`;
CREATE TABLE IF NOT EXISTS `materias` (
  `id_materias` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_materias` text COLLATE utf8_unicode_ci NOT NULL,
  `descripcion_materias` text COLLATE utf8_unicode_ci NOT NULL,
  `ano_grado` enum('1ero','2do','3ero','4to','5to','6to') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_materias`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `materias`
--

INSERT INTO `materias` (`id_materias`, `nombre_materias`, `descripcion_materias`, `ano_grado`) VALUES
(61, 'Artes plásticas', 'ascascac asaxzczx', '1ero'),
(62, 'salud', 'asdzxc', '2do'),
(66, 'Dibujo', 'Para los de secundaria', '2do');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `municipios`
--

DROP TABLE IF EXISTS `municipios`;
CREATE TABLE IF NOT EXISTS `municipios` (
  `id_municipio` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_municipio` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `id_estado` int(11) NOT NULL,
  PRIMARY KEY (`id_municipio`),
  UNIQUE KEY `nombre_municipio` (`nombre_municipio`),
  KEY `id_estado` (`id_estado`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `nombre_municipio`, `id_estado`) VALUES
(1, 'Pedro María Freites', 2),
(2, 'Simón Rodríguez', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `periodo_escolar`
--

DROP TABLE IF EXISTS `periodo_escolar`;
CREATE TABLE IF NOT EXISTS `periodo_escolar` (
  `id_perido_escolar` int(11) NOT NULL AUTO_INCREMENT,
  `ano_periodo1` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `ano_periodo2` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_perido_escolar`),
  UNIQUE KEY `ano_periodo1` (`ano_periodo1`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `periodo_escolar`
--

INSERT INTO `periodo_escolar` (`id_perido_escolar`, `ano_periodo1`, `ano_periodo2`) VALUES
(10, '1995', '1997'),
(11, '2006', '2000'),
(13, '2024', '2025');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE IF NOT EXISTS `persona` (
  `id_persona` int(11) NOT NULL AUTO_INCREMENT,
  `nombre1` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `nombre2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `apellido1` varchar(12) COLLATE utf8_unicode_ci NOT NULL,
  `apellido2` varchar(15) COLLATE utf8_unicode_ci DEFAULT NULL,
  `cedula` int(8) NOT NULL,
  `sexo` enum('M','F') COLLATE utf8_unicode_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `estado_civil` enum('Casado','Soltero','Divorciado','Otro','Viudo') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id_persona`),
  UNIQUE KEY `cedula` (`cedula`)
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`id_persona`, `nombre1`, `nombre2`, `apellido1`, `apellido2`, `cedula`, `sexo`, `fecha_nacimiento`, `estado_civil`) VALUES
(189, 'asd', NULL, 'adad', NULL, 78788780, 'M', '2010-07-20', 'Divorciado'),
(191, 'dasda', 'dasd', 'dasd', 'dad', 45455455, 'M', '2013-07-11', 'Soltero'),
(196, 'nel', NULL, 'ma', NULL, 56567567, 'M', '2002-08-04', 'Soltero'),
(212, 'nlso', 'as', 'zxc', 'cac', 78999000, 'M', '2009-07-15', 'Casado'),
(217, 'Hple', 'asdzxc', 'hjk', NULL, 56666798, 'M', '2001-09-05', 'Soltero'),
(254, 'Ahu', NULL, 'Perrox', NULL, 78098767, 'M', '2015-09-09', 'Divorciado'),
(265, 'Nilson', NULL, 'Machuca', NULL, 32123432, 'M', '2000-10-11', 'Casado'),
(267, 'nul', NULL, 'nlkl', NULL, 70968567, 'M', '2013-10-16', 'Soltero'),
(268, 'felix', NULL, 'nukn', NULL, 78098345, 'F', '2014-10-16', 'Soltero'),
(269, 'nuhihiuh', NULL, 'noioijoj', NULL, 78567345, 'M', '2014-10-16', 'Soltero'),
(270, 'yuom', NULL, 'miopoimo', NULL, 89067856, 'F', '2014-10-16', 'Soltero'),
(271, 'njknkjn', NULL, 'joijoi', NULL, 89787777, 'F', '2014-10-16', 'Divorciado'),
(272, 'nkyub', NULL, 'nkjnkui', NULL, 78978678, 'F', '2014-10-16', 'Soltero'),
(273, 'asdad', NULL, 'zxczc', NULL, 78999888, 'F', '2014-10-16', 'Soltero'),
(274, 'mlkkm', NULL, 'aszxzc', NULL, 67987908, 'M', '2014-10-22', 'Soltero'),
(275, 'aszxcz', NULL, 'ñlñm', NULL, 67566455, 'M', '2014-10-16', 'Soltero'),
(276, 'sulee', NULL, 'asesa', NULL, 78677123, 'M', '2014-10-16', 'Soltero'),
(285, 'Dinosau', NULL, 'Ghjas', NULL, 43123123, 'M', '2002-11-20', 'Soltero'),
(286, 'Dinasss', NULL, 'Gulaas', NULL, 33412311, 'M', '2003-11-20', 'Soltero'),
(287, 'Yumzxc', NULL, 'ascaczxc', NULL, 89345234, 'F', '2004-11-01', 'Soltero'),
(290, 'Nuevo', NULL, 'New', NULL, 43542456, 'M', '2002-11-27', 'Soltero'),
(292, 'Nelsoa', NULL, 'asasa', NULL, 89888111, 'M', '2004-11-21', 'Casado'),
(293, 'Profesor', 'Menor', 'Nuevo', NULL, 78122123, 'M', '2002-11-23', 'Soltero'),
(294, 'mlkmkz', NULL, 'zxczxc', NULL, 71222123, 'M', '2013-11-26', 'Soltero'),
(295, 'asdzxc', NULL, 'zxcasc', NULL, 71230982, 'M', '2013-11-26', 'Divorciado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `preguntas_respuestas_seguridad_usuario`
--

DROP TABLE IF EXISTS `preguntas_respuestas_seguridad_usuario`;
CREATE TABLE IF NOT EXISTS `preguntas_respuestas_seguridad_usuario` (
  `id_pregunt_resp` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `pregunta_texto` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `respuesta_hash` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  PRIMARY KEY (`id_pregunt_resp`),
  KEY `indice` (`usuario_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `preguntas_respuestas_seguridad_usuario`
--

INSERT INTO `preguntas_respuestas_seguridad_usuario` (`id_pregunt_resp`, `usuario_id`, `pregunta_texto`, `respuesta_hash`) VALUES
(10, 217, '¿Cuál era el nombre de tu primera mascota?', '$2y$10$hWrY9amq4paoBR.Ap/19rO52bEchXVfNM5nLf30cvrAaAP7gZwlXW'),
(11, 217, '¿Cuál es el segundo nombre de tu madre?', '$2y$10$ZU4TNHivGHVrl.96FNqxiOX3qB0DMkRuxZgWQu/PaLrZXYnIFq33S'),
(12, 217, '¿Cuál es tu comida favorita?', '$2y$10$6Vm3KCPHHY2NBpQYGn73muUmWejGD2fRdFZlWXQzPspaNkHNmvQGG'),
(16, 196, '¿Cuál era el nombre de tu primera mascota?', '$2y$10$bMFZN5h3MtzbhpS.AdG7xebeHiZCYreNDRTLLIFSDNLFv.l8EPbwy'),
(17, 196, '¿Cuál es el segundo nombre de tu madre?', '$2y$10$hReASkdyl6cajcJ/aW8Dz.waCHv4.xAWJw7xuPed9eXUkDClLzvo.'),
(18, 196, '¿Cuál es tu comida favorita?', '$2y$10$Mpc8WxGNFQS.zumQB4w.uOI/SIr/CJR7J3Hp1DMynM7H8wnVRhunu'),
(28, 292, '¿Cuál era el nombre de tu primera mascota?', '$2y$10$2oYAtvSfqIfCnRbaYSSuJehA31Wkq1Eq4QCKeyxNfbY86SDYLZFzm'),
(29, 292, '¿Cuál es el segundo nombre de tu madre?', '$2y$10$JV1rdpapBF2omPm7P0XtCuLMb7RE2JRXAgCHN8LupCuCxIwunprBe'),
(30, 292, '¿Cuál es tu comida favorita?', '$2y$10$A5FYVbgzX9RJVNzTl.0IdOy26WClhWMt3ZFRH1JhBtxjx5ecZZQQG'),
(34, 293, '¿Cuál era el nombre de tu primera mascota?', '$2y$10$ENz7NVhbbMMg992UKfAAE.A/Bjxi/Ij97aGU.Sf3/ODu7c.G.a4IC'),
(35, 293, '¿Cuál es el segundo nombre de tu madre?', '$2y$10$EsnhY0fnodVmrHRO6H0xR.U.O9lKaqDOlS31ymwp6yoQ76D91Aixi'),
(36, 293, '¿Cuál es tu comida favorita?', '$2y$10$OfyX6VbFEQmYEKUp0kDHVexIgDJeYGgKyTsDgnypGpVTU1DjOQm0m');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesor`
--

DROP TABLE IF EXISTS `profesor`;
CREATE TABLE IF NOT EXISTS `profesor` (
  `persona_id` int(11) NOT NULL,
  `fecha_contratacion` date NOT NULL,
  `estatus` enum('Activo','De licencia','Jubilado','Contrato temporal','Inactivo','Despedido') COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`persona_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `profesor`
--

INSERT INTO `profesor` (`persona_id`, `fecha_contratacion`, `estatus`) VALUES
(217, '2025-08-13', 'Jubilado'),
(290, '2025-11-21', 'Activo'),
(293, '2025-11-23', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesores_materias`
--

DROP TABLE IF EXISTS `profesores_materias`;
CREATE TABLE IF NOT EXISTS `profesores_materias` (
  `persona_id_profesor` int(11) NOT NULL,
  `materia_id` int(11) NOT NULL,
  PRIMARY KEY (`persona_id_profesor`,`materia_id`),
  KEY `profesores_materias_ibfk_2` (`materia_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `profesores_materias`
--

INSERT INTO `profesores_materias` (`persona_id_profesor`, `materia_id`) VALUES
(217, 61),
(217, 62),
(290, 62);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tokens_recuperacion`
--

DROP TABLE IF EXISTS `tokens_recuperacion`;
CREATE TABLE IF NOT EXISTS `tokens_recuperacion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `expires_at` datetime NOT NULL,
  `used_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `token` (`token`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `tokens_recuperacion`
--

INSERT INTO `tokens_recuperacion` (`id`, `user_id`, `token`, `expires_at`, `used_at`, `created_at`) VALUES
(25, 196, 'b0015bcb6d70e187a86513f43bec4409aaca48d7918bf3c78214232487001647', '2025-11-14 14:35:27', NULL, '2025-11-14 18:25:27'),
(27, 196, '2a4909d42c9910c9ff3877c82f5b5cc96f730f1b23404242bd0e7e3e19fcc2f2', '2025-11-14 14:38:03', NULL, '2025-11-14 18:28:03'),
(28, 196, '120002dfe4562bc4dda4e6c94eae59408344f355ebc2d51ef4d91b1d5f941a55', '2025-11-14 14:44:02', NULL, '2025-11-14 18:34:02'),
(29, 196, 'ce62d7816d2675e124ade9241ffad1e81db5c88b19c35bcb59a083224e472a50', '2025-11-14 14:44:58', NULL, '2025-11-14 18:34:58'),
(30, 196, '7f3de548c1a6f48b40584164e7f95f663b121764c3a40f75b28c29850066f5eb', '2025-11-14 14:46:12', NULL, '2025-11-14 18:36:12'),
(31, 196, 'b46674bdd156a7c52f17a5b811389785b8dbd9de779d0f2cfa8af7115774fec5', '2025-11-14 15:10:49', NULL, '2025-11-14 19:00:49'),
(32, 196, 'ab135d18058dabe49b9fba43d92e216b396ba0bfe638977e6bef8e4c6fd8ff26', '2025-11-05 12:35:35', NULL, '2025-11-05 16:25:35'),
(33, 196, 'cb3e6d526684ad4ae4a486aefc9d72ced9d7ea7c99d68f09e5bcd547362689af', '2025-11-05 13:02:46', NULL, '2025-11-05 16:52:46'),
(34, 196, 'ae764112afb31b29f1f2a02bbc3017a6ee0292ca358fd3e9ab775e731d9ba7f6', '2025-11-05 20:35:40', NULL, '2025-11-06 00:05:40'),
(35, 196, 'bb5f98b529ee1c61815f48e6030b7633924cfdaade235d98716d2e77e0908b2e', '2025-11-16 16:12:53', '2025-11-16 16:09:51', '2025-11-16 16:06:53');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicacion`
--

DROP TABLE IF EXISTS `ubicacion`;
CREATE TABLE IF NOT EXISTS `ubicacion` (
  `id_persona_ubicacion` int(11) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `id_municipio_u` int(11) DEFAULT NULL,
  `id_comunidad` int(11) DEFAULT NULL,
  `municipio_texto` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  `ciudad_comunidad_texto` varchar(150) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id_persona_ubicacion`),
  KEY `id_estado` (`id_estado`,`id_municipio_u`,`id_comunidad`),
  KEY `relacion_ubicacion_municipio` (`id_municipio_u`),
  KEY `relacion_ubicacion_comunidad` (`id_comunidad`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ubicacion`
--

INSERT INTO `ubicacion` (`id_persona_ubicacion`, `id_estado`, `id_municipio_u`, `id_comunidad`, `municipio_texto`, `ciudad_comunidad_texto`) VALUES
(189, 11, NULL, NULL, 'asdad', 'asda'),
(191, 3, NULL, NULL, 'dasda', 'asdad'),
(212, 10, NULL, NULL, 'zxczc', 'asd'),
(217, 2, 1, 27, NULL, NULL),
(254, 3, NULL, NULL, 'zxczxc', NULL),
(265, 8, NULL, NULL, 'Hlas', 'HEAS'),
(267, 2, 1, 10, NULL, NULL),
(268, 10, NULL, NULL, 'kjnkj', NULL),
(269, 3, NULL, NULL, 'knjnknk', NULL),
(270, 4, NULL, NULL, 'jnkjnk', NULL),
(271, 6, NULL, NULL, 'jnkjnk', NULL),
(272, 10, NULL, NULL, 'nkjnk', NULL),
(273, 6, NULL, NULL, 'zxczc', 'asdad'),
(274, 3, NULL, NULL, 'zczxaxc', 'mlkmlk'),
(275, 3, NULL, NULL, 'zczcas', 'zcza'),
(276, 8, NULL, NULL, 'zxczca', 'zczc'),
(285, 6, NULL, NULL, 'zxcasc', 'asczxc'),
(286, 10, NULL, NULL, 'zxcasc', 'zxcasc'),
(287, 7, NULL, NULL, 'Albino', 'Blacks'),
(290, 7, NULL, NULL, 'asdczxc', 'zxcascz'),
(292, 10, NULL, NULL, 'asdascz', 'asczxc'),
(293, 9, NULL, NULL, 'zxcasc', 'asczxc'),
(294, 6, NULL, NULL, 'zxcasc', 'asdazxc'),
(295, 6, NULL, NULL, 'asdacz', 'ascaczxc');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `persona_id` int(11) NOT NULL,
  `nombre_usuario` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(1200) COLLATE utf8_unicode_ci NOT NULL,
  `rol_usuario` enum('administrador','asistente','Profesor') COLLATE utf8_unicode_ci NOT NULL,
  `preguntas_seguridad_configuradas` tinyint(1) NOT NULL,
  `reinicio_habilitado` tinyint(1) NOT NULL,
  `reinicio_habilitado_duracion` datetime DEFAULT NULL,
  `solicitud_reinicio` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`persona_id`),
  UNIQUE KEY `nombre_usuario` (`nombre_usuario`),
  KEY `persona_id` (`persona_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`persona_id`, `nombre_usuario`, `password`, `rol_usuario`, `preguntas_seguridad_configuradas`, `reinicio_habilitado`, `reinicio_habilitado_duracion`, `solicitud_reinicio`) VALUES
(196, 'asdad145', '$2y$10$meSEilAYEw7WXeUSKAgHweBazJq3.aGMbsFwdazHgiXeYuwszVONS', 'administrador', 1, 0, '2025-08-02 00:00:00', 0),
(217, 'Nelson280', '$2y$10$ojue1buWzvgMH9EvtkRrrOLhXpWHB1wLitxyeCbtop3HocaP2ZI4C', 'Profesor', 1, 1, '2025-08-29 10:39:00', 0),
(265, 'Poder1234', 'cascacdasdasdf123', 'asistente', 0, 0, NULL, 0),
(285, 'Nammsa123', 'Casioman28#', 'asistente', 0, 0, NULL, 0),
(286, 'Namm2313', '$2y$10$3cMoasOTx2D998RMct9x0eEr4mqmLL2XWw5.rIZNtBugJevgdLDxi', 'asistente', 0, 0, NULL, 0),
(290, 'Cuida1312', '$2y$10$w3YU/mIzlMDXDRsqQGIJ0OIb3RljkQkXdKrGXvHgOVM7.Z6NsCzqy', 'Profesor', 0, 0, NULL, 0),
(292, 'Hola123', '$2y$10$1CJmSiCUQi163oGU3zFCDOE5aUdXnEE9t2zwRnfdTzCVM9b17NQJ6', 'asistente', 1, 0, NULL, 0),
(293, 'Profesor123', '$2y$10$/DcI/09zcVgHHesCIsMXWuhihPFrP0LhoFcS.AjMXCr.9RAgfvEUK', 'Profesor', 1, 0, NULL, 0);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `comunidades`
--
ALTER TABLE `comunidades`
  ADD CONSTRAINT `relacion_comunidades_municipios` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `estudiante`
--
ALTER TABLE `estudiante`
  ADD CONSTRAINT `estudiante_ibfk_2` FOREIGN KEY (`periodo_escolar_id`) REFERENCES `periodo_escolar` (`id_perido_escolar`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relacion_persona_estudiante` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `relacion_municipios_estados` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estados`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `preguntas_respuestas_seguridad_usuario`
--
ALTER TABLE `preguntas_respuestas_seguridad_usuario`
  ADD CONSTRAINT `relacion_usuario_pre_seguridad` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`persona_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesor`
--
ALTER TABLE `profesor`
  ADD CONSTRAINT `profesor_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `profesores_materias`
--
ALTER TABLE `profesores_materias`
  ADD CONSTRAINT `profesores_materias_ibfk_1` FOREIGN KEY (`persona_id_profesor`) REFERENCES `profesor` (`persona_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `profesores_materias_ibfk_2` FOREIGN KEY (`materia_id`) REFERENCES `materias` (`id_materias`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `tokens_recuperacion`
--
ALTER TABLE `tokens_recuperacion`
  ADD CONSTRAINT `tokens_recuperacion_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`persona_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `ubicacion`
--
ALTER TABLE `ubicacion`
  ADD CONSTRAINT `relacion_ubicacion_comunidad` FOREIGN KEY (`id_comunidad`) REFERENCES `comunidades` (`id_comunidad`),
  ADD CONSTRAINT `relacion_ubicacion_estado` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estados`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relacion_ubicacion_municipio` FOREIGN KEY (`id_municipio_u`) REFERENCES `municipios` (`id_municipio`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `relacion_ubicacion_persona` FOREIGN KEY (`id_persona_ubicacion`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`id_persona`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
