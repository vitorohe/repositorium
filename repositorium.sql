-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 06-05-2012 a las 23:15:16
-- Versión del servidor: 5.5.20
-- Versión de PHP: 5.3.10

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `repositorium`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `activations`
--

CREATE TABLE IF NOT EXISTS `activations` (
  `id` char(1) NOT NULL COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Estado de Activacion',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  PRIMARY KEY (`id`),
  KEY `activacions_01` (`internalstate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Estado de Activacion de los Registros del Sistema';

--
-- Volcado de datos para la tabla `activations`
--

INSERT INTO `activations` (`id`, `name`, `internalstate_id`) VALUES
('A', 'Activo', 'A'),
('N', 'No Activo', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `attachfiles`
--

CREATE TABLE IF NOT EXISTS `attachfiles` (
  `id` int(20) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Documento',
  `location` varchar(100) NOT NULL COMMENT 'Ubicacion del Documento Adjunto en el Sistema',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `document_id` int(15) NOT NULL COMMENT 'Clave Foranea Documentos',
  PRIMARY KEY (`id`),
  KEY `documento_adjuntos_01` (`activation_id`),
  KEY `documento_adjuntos_02` (`internalstate_id`),
  KEY `documento_adjuntos_03` (`document_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `attachfiles`
--

INSERT INTO `attachfiles` (`id`, `name`, `location`, `activation_id`, `internalstate_id`, `document_id`) VALUES
(8, '46906bcc.gif', '/uploaded_files', 'A', 'A', 152),
(9, 'Startled_hamster_01.gif', '/uploaded_files', 'A', 'A', 153),
(10, 'seven.png', '/uploaded_files', 'A', 'A', 154);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre de la Categoria',
  `description` text NOT NULL COMMENT 'Descripcion de la Categoria',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios (Creador de la Catergoria)',
  PRIMARY KEY (`id`),
  KEY `categorias_01` (`activation_id`),
  KEY `categorias_02` (`internalstate_id`),
  KEY `categorias_03` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Categorias del Sistema' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `category_criterias`
--

CREATE TABLE IF NOT EXISTS `category_criterias` (
  `id` int(10) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `category_id` int(4) NOT NULL COMMENT 'Clave Foranea Categorias',
  `criteria_id` int(6) NOT NULL COMMENT 'Clave Foranea Criterios',
  PRIMARY KEY (`id`),
  KEY `categoria_criterios_01` (`activation_id`),
  KEY `categoria_criterios_02` (`internalstate_id`),
  KEY `categoria_criterios_03` (`category_id`),
  KEY `categoria_criterios_04` (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Criterios que pertenecen a una Categoria' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criterias`
--

CREATE TABLE IF NOT EXISTS `criterias` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Criterio',
  `question` text NOT NULL COMMENT 'Descripcion del Criterio',
  `upload_score` int(4) NOT NULL COMMENT 'Puntaje para poder Subir un Documento',
  `download_score` int(4) NOT NULL COMMENT 'Puntaje para poder Bajar un Documento',
  `collaboration_score` int(4) NOT NULL COMMENT 'Puntaje que uno Logra al Compartir un Documento',
  `register_date` datetime NOT NULL COMMENT 'Fecha de Creacion del Criterio',
  `register_ip` varchar(25) NOT NULL COMMENT 'IP de Creacion del Criterio',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios (Creador del Criterio)',
  PRIMARY KEY (`id`),
  KEY `criterios_01` (`activation_id`),
  KEY `criterios_02` (`internalstate_id`),
  KEY `criterios_03` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `criterias`
--

INSERT INTO `criterias` (`id`, `name`, `question`, `upload_score`, `download_score`, `collaboration_score`, `register_date`, `register_ip`, `activation_id`, `internalstate_id`, `user_id`) VALUES
(1, 'Perro', 'is it a perro?', 1, 1, 1, '0000-00-00 00:00:00', '', 'A', 'A', 2),
(2, 'gato', 'is it a cat?', 1, 1, 1, '0000-00-00 00:00:00', '', 'A', 'A', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criterias_documents`
--

CREATE TABLE IF NOT EXISTS `criterias_documents` (
  `id` int(21) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `document_id` int(15) NOT NULL COMMENT 'Clave Foranea Documentos',
  `criteria_id` int(6) NOT NULL COMMENT 'Clave Foranea Criterios',
  `answer` int(2) NOT NULL DEFAULT '3',
  PRIMARY KEY (`id`),
  KEY `documento_criterios_01` (`activation_id`),
  KEY `documento_criterios_02` (`internalstate_id`),
  KEY `documento_criterios_03` (`document_id`),
  KEY `documento_criterios_04` (`criteria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=117 ;

--
-- Volcado de datos para la tabla `criterias_documents`
--

INSERT INTO `criterias_documents` (`id`, `activation_id`, `internalstate_id`, `document_id`, `criteria_id`, `answer`) VALUES
(1, 'A', 'A', 36, 1, 3),
(2, 'A', 'A', 37, 1, 3),
(3, 'A', 'A', 38, 1, 3),
(4, 'A', 'A', 38, 2, 3),
(5, 'A', 'A', 39, 2, 3),
(6, 'A', 'A', 40, 1, 3),
(7, 'A', 'A', 40, 2, 3),
(8, 'A', 'A', 41, 1, 3),
(9, 'A', 'A', 42, 2, 3),
(10, 'A', 'A', 43, 2, 3),
(11, 'A', 'A', 44, 2, 3),
(12, 'A', 'A', 45, 1, 3),
(13, 'A', 'A', 46, 2, 3),
(14, 'A', 'A', 47, 2, 3),
(15, 'A', 'A', 48, 2, 3),
(16, 'A', 'A', 49, 2, 3),
(17, 'A', 'A', 50, 2, 3),
(18, 'A', 'A', 51, 2, 3),
(19, 'A', 'A', 52, 2, 3),
(20, 'A', 'A', 53, 2, 3),
(21, 'A', 'A', 54, 2, 3),
(22, 'A', 'A', 55, 1, 3),
(23, 'A', 'A', 56, 2, 3),
(24, 'A', 'A', 57, 1, 3),
(25, 'A', 'A', 58, 1, 3),
(26, 'A', 'A', 59, 1, 3),
(27, 'A', 'A', 60, 2, 3),
(28, 'A', 'A', 61, 2, 3),
(29, 'A', 'A', 62, 2, 3),
(30, 'A', 'A', 63, 2, 3),
(31, 'A', 'A', 65, 2, 3),
(32, 'A', 'A', 70, 2, 3),
(33, 'A', 'A', 73, 2, 3),
(34, 'A', 'A', 74, 1, 3),
(35, 'A', 'A', 75, 1, 3),
(36, 'A', 'A', 76, 1, 3),
(37, 'A', 'A', 77, 1, 3),
(38, 'A', 'A', 78, 1, 3),
(39, 'A', 'A', 79, 1, 3),
(40, 'A', 'A', 80, 1, 3),
(41, 'A', 'A', 81, 1, 3),
(42, 'A', 'A', 82, 1, 3),
(43, 'A', 'A', 83, 1, 3),
(44, 'A', 'A', 84, 2, 3),
(45, 'A', 'A', 85, 1, 3),
(46, 'A', 'A', 86, 1, 3),
(47, 'A', 'A', 87, 1, 3),
(48, 'A', 'A', 88, 1, 3),
(49, 'A', 'A', 89, 1, 3),
(50, 'A', 'A', 90, 1, 3),
(51, 'A', 'A', 91, 2, 3),
(52, 'A', 'A', 92, 2, 3),
(53, 'A', 'A', 93, 1, 3),
(54, 'A', 'A', 94, 1, 3),
(55, 'A', 'A', 95, 1, 3),
(56, 'A', 'A', 96, 1, 3),
(57, 'A', 'A', 97, 1, 3),
(58, 'A', 'A', 98, 1, 3),
(59, 'A', 'A', 99, 1, 3),
(60, 'A', 'A', 100, 1, 3),
(61, 'A', 'A', 101, 1, 3),
(62, 'A', 'A', 102, 1, 3),
(63, 'A', 'A', 103, 1, 3),
(64, 'A', 'A', 104, 1, 3),
(65, 'A', 'A', 105, 1, 3),
(66, 'A', 'A', 106, 1, 3),
(67, 'A', 'A', 107, 1, 3),
(68, 'A', 'A', 108, 1, 3),
(69, 'A', 'A', 109, 1, 3),
(70, 'A', 'A', 110, 1, 3),
(71, 'A', 'A', 111, 2, 3),
(72, 'A', 'A', 112, 2, 3),
(73, 'A', 'A', 113, 2, 3),
(74, 'A', 'A', 114, 2, 3),
(75, 'A', 'A', 115, 2, 3),
(76, 'A', 'A', 116, 2, 3),
(77, 'A', 'A', 117, 2, 3),
(78, 'A', 'A', 118, 2, 3),
(79, 'A', 'A', 119, 2, 3),
(80, 'A', 'A', 120, 2, 3),
(81, 'A', 'A', 121, 2, 3),
(82, 'A', 'A', 122, 1, 3),
(83, 'A', 'A', 123, 1, 3),
(84, 'A', 'A', 124, 1, 3),
(85, 'A', 'A', 125, 1, 3),
(86, 'A', 'A', 126, 1, 3),
(87, 'A', 'A', 127, 1, 3),
(88, 'A', 'A', 128, 1, 3),
(89, 'A', 'A', 129, 1, 3),
(90, 'A', 'A', 130, 1, 3),
(91, 'A', 'A', 131, 1, 3),
(92, 'A', 'A', 132, 1, 3),
(93, 'A', 'A', 133, 1, 3),
(94, 'A', 'A', 134, 1, 3),
(95, 'A', 'A', 135, 1, 3),
(96, 'A', 'A', 136, 1, 3),
(97, 'A', 'A', 137, 1, 3),
(98, 'A', 'A', 138, 1, 3),
(99, 'A', 'A', 139, 1, 3),
(100, 'A', 'A', 140, 1, 3),
(101, 'A', 'A', 141, 1, 3),
(102, 'A', 'A', 142, 2, 3),
(103, 'A', 'A', 143, 1, 3),
(104, 'A', 'A', 144, 1, 3),
(105, 'A', 'A', 145, 1, 3),
(106, 'A', 'A', 146, 1, 3),
(107, 'A', 'A', 147, 1, 3),
(108, 'A', 'A', 148, 2, 3),
(109, 'A', 'A', 149, 2, 3),
(110, 'A', 'A', 150, 2, 3),
(111, 'A', 'A', 151, 1, 3),
(112, 'A', 'A', 152, 1, 3),
(113, 'A', 'A', 153, 2, 3),
(114, 'A', 'A', 153, 1, 3),
(115, 'A', 'A', 154, 1, 3),
(116, 'A', 'A', 154, 2, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criterias_users`
--

CREATE TABLE IF NOT EXISTS `criterias_users` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `successful_evaluation` int(10) NOT NULL COMMENT 'Cantidad de Evaluacion Exitosas de Documentos',
  `negative_evaluation` int(10) NOT NULL COMMENT 'Cantidad de Evaluacion Negativas de Documentos',
  `score_obtained` int(10) NOT NULL COMMENT 'Puntaje del Usuario obtenido en el Criterio',
  `expert_user` char(1) NOT NULL COMMENT 'Verificar si el Usuario es Experto o no en el Criterio',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios',
  `criteria_id` int(6) NOT NULL COMMENT 'Clave Foranea Criterios',
  `quality_user_id` int(2) NOT NULL COMMENT 'Clave Foranea Calidad_Usuarios',
  PRIMARY KEY (`id`),
  KEY `usuario_criterios_01` (`expert_user`),
  KEY `usuario_criterios_02` (`activation_id`),
  KEY `usuario_criterios_03` (`internalstate_id`),
  KEY `usuario_criterios_04` (`user_id`),
  KEY `usuario_criterios_05` (`criteria_id`),
  KEY `usuario_criterios_06` (`quality_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Usuarios asociados a los Criterios para almacenar el Puntaje' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documents`
--

CREATE TABLE IF NOT EXISTS `documents` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Documento',
  `description` text NOT NULL COMMENT 'Descripcion del Documento',
  `register_date` datetime NOT NULL COMMENT 'Fecha de la Subida del Documento',
  `register_ip` varchar(25) NOT NULL COMMENT 'IP de la Subida del Documento',
  `registration_date` datetime NOT NULL COMMENT 'Fecha del Alta del Documento',
  `registration_ip` varchar(25) NOT NULL COMMENT 'IP del Alta del Documento',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios',
  `repository_id` int(6) NOT NULL COMMENT 'Clave Foranea Repositorios',
  `document_state_id` int(2) NOT NULL COMMENT 'Clave Foranea Estado_Documentos',
  PRIMARY KEY (`id`),
  KEY `documentos_01` (`activation_id`),
  KEY `documentos_02` (`internalstate_id`),
  KEY `documentos_05` (`document_state_id`),
  KEY `documentos_03` (`user_id`),
  KEY `documentos_04` (`repository_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Documentos subidos por los Usuarios al Repositorio' AUTO_INCREMENT=155 ;

--
-- Volcado de datos para la tabla `documents`
--

INSERT INTO `documents` (`id`, `name`, `description`, `register_date`, `register_ip`, `registration_date`, `registration_ip`, `activation_id`, `internalstate_id`, `user_id`, `repository_id`, `document_state_id`) VALUES
(5, 'jkkjkj', 'kjkjkj', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(30, 'hjjhhjjhklfdskm', 'jhbskjdngkjdsnjkd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(31, 'kjkjkjjkdfsklgm', 'msfdmgdsmklg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(32, 'jkkjkjdfs', 'fdsklgdsk', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(33, 'saffasf', 'klasdogfsakofoas', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(34, 'dsgdslhsdlkdslk', 'flksglkdsajgkldsjhdkl', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(35, 'fdsg', 'fsklgdsmkl', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(36, 'hola', 'hola', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(37, 'hola', 'hola', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(38, 'hola2', 'hola2', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(39, 'doc3', 'doc3', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(40, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(41, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(42, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(43, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(44, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(45, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(46, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(47, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(48, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(49, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(50, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(51, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(52, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(53, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(54, 'doc4', 'doc4', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(55, 'sdf', 'sdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(56, 'sadfsdf', 'sdfsg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(57, 'sadfsdf', 'sdfsg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(58, 'sadfsdf', 'sdfsg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(59, 'sdfsdf', 'sdfsdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(60, 'sdgbg', 'sdvtsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(61, 'sdgbg', 'sdvtsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(62, 'sdgbg', 'sdvtsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(63, 'sdgbg', 'sdvtsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(65, 'sdfsdf', 'sdfsdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(70, 'sdfs', 'dfefsdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(73, 'df', 'sdfsdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(74, 'sfgrgr', 'sdfgresd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(75, 'sfgrgr', 'sdfgresd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(76, 'sfgrgr', 'sdfgresd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(77, 'dafsdf', 'dsfsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(78, 'dafsdf', 'dsfsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(79, 'dafsdf', 'dsfsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(80, 'dafsdf', 'dsfsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(81, 'dafsdf', 'dsfsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(82, 'dafsdf', 'dsfsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(83, 'dafsdf', 'dsfsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(84, 'dsfsdf', 'sdfsdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(85, 'dsfsdf', 'sdfsdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(86, 'fewfdsf', 'fwfdsgfrfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(87, 'fewfdsf', 'fwfdsgfrfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(88, 'sdgrg', 'sgfsfe', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(89, 'sdgrg', 'sgfsfe', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(90, 'fefsdfe', 'sfsdfe', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(91, 'efsd', 'feasdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(92, 'efsd', 'feasdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(93, 'vfdvdsfcgv', 'rgsdrdfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(94, 'vfdvdsfcgv', 'rgsdrdfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(95, 'vfdvdsfcgv', 'rgsdrdfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(96, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(97, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(98, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(99, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(100, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(101, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(102, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(103, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(104, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(105, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(106, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(107, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(108, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(109, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(110, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(111, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(112, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(113, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(114, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(115, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(116, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(117, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(118, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(119, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(120, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(121, 'dfushdfo', 'qhodhsfosdhfos', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(122, 'wefdsfg', 'rfsgfe', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(123, 'wefdsfg', 'rfsgfe', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(124, 'dfgdfg', 'drfgrgsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(125, 'dfgdfg', 'drfgrgsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(126, 'dfgdfg', 'drfgrgsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(127, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(128, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(129, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(130, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(131, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(132, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(133, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(134, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(135, 'sdfgwrg', 'fsdgregfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(136, 'dfgdfg', 'drfgrgsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(137, 'dfgdfg', 'drfgrgsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(138, 'ergdf', 'grgfgdfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(139, 'gsgrsfd', 'gsdfsee', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(140, 'gsgrsfd', 'gsdfsee', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(141, 'vdfgr', 'fgdfghr', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(142, 'refgverg', 'sdfgr ', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(143, 'rregsfg', 'rsffgsfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(144, 'rregsfg', 'rsffgsfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(145, 'rregsfg', 'rsffgsfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(146, 'rregsfg', 'rsffgsfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(147, 'rregsfg', 'rsffgsfg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(148, 'fgr', 'sfgfdhdg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(149, 'dfsgr', 'sfgrsgfd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(150, 'rgsgsfg', 'rgsfgdfgdf', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(151, 'fhtd', 'gfhdgfh', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(152, 'gtrgd', 'fhdghtr', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1),
(153, 'docn', 'People are often unreasonable, illogical and self centered;\r\nForgive them anyway.\r\nIf you are kind, people may accuse you of selfish, ulterior motives;\r\nBe kind anyway.\r\nIf you are successful, you will win some false friends and some true enemies;\r\nSucceed anyway.\r\nIf you are honest and frank, people may cheat you;\r\nBe honest and frank anyway.\r\nWhat you spend years building, someone could destroy overnight;\r\nBuild anyway.\r\nIf you find serenity and happiness, they may be jealous;\r\nBe happy anyway.\r\nThe good you do today, people will often forget tomorrow;\r\nDo good anyway.\r\nGive the world the best you have, and it may never be enough;\r\nGive the world the best you''ve got anyway.\r\nYou see, in the final analysis, it is between you and your God;\r\nIt was never between you and them anyway.', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 1, 1),
(154, 'docnn', 'A wiki (i/ËˆwÉªki/ WIK-ee) is a website whose users can add, modify, or delete its content via a web browser using a simplified markup language or a rich-text editor.[1][2][3] Wikis are typically powered by wiki software and are often created collaboratively by multiple users. Examples include community websites, corporate intranets, knowledge management systems, and notetaking.\r\n\r\nWikis may serve many different purposes. Some permit control over different functions (levels of access). For example, editing rights may permit changing, adding or removing material. Others may permit access without enforcing access control. Other rules may also be imposed for organizing content.\r\n\r\nWard Cunningham, the developer of the first wiki software, WikiWikiWeb, originally described it as "the simplest online database that could possibly work."[4] "Wiki" (pronounced [Ëˆwiti] or [Ëˆviti]) is an Hawaiian word meaning "fast" or "quick".[5]', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 2, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `document_states`
--

CREATE TABLE IF NOT EXISTS `document_states` (
  `id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Estado del Documento',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  PRIMARY KEY (`id`),
  KEY `estado_documentos_01` (`activation_id`),
  KEY `estado_documentos_02` (`internalstate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Estado de los Documentos del Repositorio' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `document_states`
--

INSERT INTO `document_states` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES
(1, 'Subido', 'A', 'A'),
(2, 'En proceso de evaluacion experta', 'A', 'A'),
(3, 'Validado por experto', 'A', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `internalstates`
--

CREATE TABLE IF NOT EXISTS `internalstates` (
  `id` char(1) NOT NULL COMMENT 'Identificador',
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Estados Internos de los Registros del Sistema';

--
-- Volcado de datos para la tabla `internalstates`
--

INSERT INTO `internalstates` (`id`, `name`) VALUES
('A', 'Activo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modules`
--

CREATE TABLE IF NOT EXISTS `modules` (
  `id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Modulo del Sistema',
  `description` text NOT NULL COMMENT 'Descripcion del Modulo del Sistema',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  PRIMARY KEY (`id`),
  KEY `modulos_01` (`activation_id`),
  KEY `modulos_02` (`internalstate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Modulos del Sistema' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `payed_searches`
--

CREATE TABLE IF NOT EXISTS `payed_searches` (
  `id` int(15) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `total_spent_points` int(6) NOT NULL COMMENT 'Puntos Gastados en Total',
  `documents_amount` int(4) NOT NULL COMMENT 'Cantidad de Documentos Solicitados',
  `register_date` datetime NOT NULL COMMENT 'Fecha de la Solicitud de Busqueda',
  `register_ip` varchar(25) NOT NULL COMMENT 'IP de la Solicitud de Busqueda',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios',
  PRIMARY KEY (`id`),
  KEY `busqueda_pagadas_01` (`activation_id`),
  KEY `busqueda_pagadas_02` (`internalstate_id`),
  KEY `busqueda_pagadas_03` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Datos Generales de la Busqueda Pagada' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `privileges`
--

CREATE TABLE IF NOT EXISTS `privileges` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `permission_reading` char(1) NOT NULL COMMENT 'Permiso de Lectura en el Submodulo',
  `permission_writing` char(1) NOT NULL COMMENT 'Permiso de Escritura en el Submodulo',
  `permission_execution` char(1) NOT NULL COMMENT 'Permiso de Ejecucion en el Submodulo',
  `permission_impresion` char(1) NOT NULL COMMENT 'Permiso de Impresion en el Submodulo',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_type_id` int(2) NOT NULL COMMENT 'Clave Foranea Tipo_Usuarios',
  `submodule_id` int(4) NOT NULL COMMENT 'Clave Foranea Modulos',
  PRIMARY KEY (`id`),
  KEY `privilegios_01` (`permission_reading`),
  KEY `privilegios_02` (`permission_writing`),
  KEY `privilegios_03` (`permission_execution`),
  KEY `privilegios_04` (`permission_impresion`),
  KEY `privilegios_05` (`activation_id`),
  KEY `privilegios_06` (`internalstate_id`),
  KEY `privilegios_07` (`user_type_id`),
  KEY `privilegios_08` (`submodule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Privilegios de los Tipos de Usuarios en el Sistema' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `quality_users`
--

CREATE TABLE IF NOT EXISTS `quality_users` (
  `id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre de la Calidad del Usuario (Nombre otorgado por la Participacion en el Sistema)',
  `description` text NOT NULL COMMENT 'Descripcion de la Calidad del Usuario',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `percentage` int(3) DEFAULT NULL COMMENT 'Porcentaje de Aceptacion de la Calidad de Usuario',
  PRIMARY KEY (`id`),
  KEY `calidad_usuarios_01` (`activation_id`),
  KEY `calidad_usuarios_02` (`internalstate_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repositories`
--

CREATE TABLE IF NOT EXISTS `repositories` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Repositorio',
  `internal_name` varchar(50) NOT NULL COMMENT 'Nombre Interno del Repositorio',
  `description` text COMMENT 'Descripcion del Repositorio',
  `created` datetime NOT NULL COMMENT 'Fecha de Creacion del Repositorio',
  `modifed` datetime DEFAULT NULL COMMENT 'Fecha de la Ultima Modificacion del Repositorio',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios',
  PRIMARY KEY (`id`),
  KEY `repositorios_01` (`activation_id`),
  KEY `repositorios_02` (`internalstate_id`),
  KEY `repositorios_03` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Repositorio de Documentos del Sistema' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `repositories`
--

INSERT INTO `repositories` (`id`, `name`, `internal_name`, `description`, `created`, `modifed`, `activation_id`, `internalstate_id`, `user_id`) VALUES
(1, 'prueba', 'prueba', 'prueba', '2012-04-30 02:47:58', NULL, 'A', 'A', 3),
(2, 'prueba2', 'prueba2', 'prueba2', '2012-05-01 00:38:29', NULL, 'A', 'A', 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repositories_users`
--

CREATE TABLE IF NOT EXISTS `repositories_users` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios',
  `repository_id` int(6) NOT NULL COMMENT 'Clave Foranea Repositorios',
  `user_type_id` int(2) NOT NULL COMMENT 'Clave Foranea Tipo_Usuarios',
  PRIMARY KEY (`id`),
  KEY `usuario_repositorios_01` (`activation_id`),
  KEY `usuario_repositorios_02` (`internalstate_id`),
  KEY `usuario_repositorios_03` (`user_id`),
  KEY `usuario_repositorios_04` (`repository_id`),
  KEY `usuario_repositorios_05` (`user_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `repositories_users`
--

INSERT INTO `repositories_users` (`id`, `activation_id`, `internalstate_id`, `user_id`, `repository_id`, `user_type_id`) VALUES
(2, 'A', 'A', 2, 1, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repository_restrictions`
--

CREATE TABLE IF NOT EXISTS `repository_restrictions` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre de la Restriccion',
  `extension` varchar(10) NOT NULL COMMENT 'Extension del Archivo que se Permite Subir al Repositorio',
  `seize` int(10) NOT NULL COMMENT 'Peso Permitido del Archivo Adjunto',
  `amount` int(5) NOT NULL COMMENT 'Cantidad de Archivos Adjuntos Permitidos',
  `register_date` datetime NOT NULL COMMENT 'Fecha de Creacion de la Restriccion',
  `register_ip` varchar(25) NOT NULL COMMENT 'IP de Creacion de la Restriccion',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios',
  `repository_id` int(6) NOT NULL COMMENT 'Clave Foranea Repositorios',
  PRIMARY KEY (`id`),
  KEY `repositorio_restriccions_01` (`activation_id`),
  KEY `repositorio_restriccions_02` (`internalstate_id`),
  KEY `repositorio_restriccions_03` (`user_id`),
  KEY `repositorio_restriccions_04` (`repository_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `search_criterias`
--

CREATE TABLE IF NOT EXISTS `search_criterias` (
  `id` int(21) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `spent_points` int(6) NOT NULL COMMENT 'Puntos Gastados por el Criterio',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `payed_search_id` int(15) NOT NULL COMMENT 'Clave Foranea Busqueda_Pagadas',
  `criteria_id` int(6) NOT NULL COMMENT 'Clave Foranea Criterios',
  PRIMARY KEY (`id`),
  KEY `busqueda_criterios_01` (`activation_id`),
  KEY `busqueda_criterios_02` (`internalstate_id`),
  KEY `busqueda_criterios_03` (`payed_search_id`),
  KEY `busqueda_criterios_04` (`criteria_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `submodules`
--

CREATE TABLE IF NOT EXISTS `submodules` (
  `id` int(4) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Submodulo',
  `description` text NOT NULL COMMENT 'Descripcion del Submodulo',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `module_id` int(2) NOT NULL COMMENT 'Clave Foranea Modulos',
  PRIMARY KEY (`id`),
  KEY `submodulos_01` (`activation_id`),
  KEY `submodulos_02` (`internalstate_id`),
  KEY `submodulos_03` (`module_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Submodulos del Sistema' AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Usuario',
  `email` varchar(150) NOT NULL COMMENT 'Correo Electronico del Usuario',
  `username` varchar(50) NOT NULL COMMENT 'Nombre de Usuario en el Sistema',
  `password` varchar(50) NOT NULL COMMENT 'Clave de Acceso en el Sistema',
  `salt` varchar(100) NOT NULL,
  `is_administrator` char(1) NOT NULL COMMENT 'El usuario es Administrador del Sistema',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  PRIMARY KEY (`id`),
  KEY `users_01` (`activation_id`),
  KEY `users_02` (`internalstate_id`),
  KEY `users_03` (`is_administrator`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Usuarios del Sistema' AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `salt`, `is_administrator`, `activation_id`, `internalstate_id`) VALUES
(2, 'admin', 'admin@example.com', 'admin', 'fbe82ab72970b9940724512227185348eac9d7fd', '1738993739', '1', 'A', 'A'),
(3, 'victor', 'vitorohe@gmail.com', 'vitorohe', '9abbf9bf2d11d1399a58f282576b86821b945aca', '1974672534', '0', 'A', 'A');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `user_types`
--

CREATE TABLE IF NOT EXISTS `user_types` (
  `id` int(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre del Tipo de Usuario',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  PRIMARY KEY (`id`),
  KEY `tipo_usuarios_01` (`activation_id`),
  KEY `tipo_usuarios_02` (`internalstate_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Tipos de Usuarios del Sistema' AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `user_types`
--

INSERT INTO `user_types` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES
(1, 'Administrador del Repositorio', 'A', 'A'),
(2, 'Usuario Normal', 'A', 'A');

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `activations`
--
ALTER TABLE `activations`
  ADD CONSTRAINT `activacions_01` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `attachfiles`
--
ALTER TABLE `attachfiles`
  ADD CONSTRAINT `documento_adjuntos_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documento_adjuntos_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documento_adjuntos_03` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categorias_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `categorias_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `categorias_03` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `category_criterias`
--
ALTER TABLE `category_criterias`
  ADD CONSTRAINT `categoria_criterios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `categoria_criterios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `categoria_criterios_03` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `categoria_criterios_04` FOREIGN KEY (`criteria_id`) REFERENCES `criterias` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `criterias`
--
ALTER TABLE `criterias`
  ADD CONSTRAINT `criterios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `criterios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `criterios_03` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `criterias_documents`
--
ALTER TABLE `criterias_documents`
  ADD CONSTRAINT `documento_criterios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documento_criterios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documento_criterios_03` FOREIGN KEY (`document_id`) REFERENCES `documents` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documento_criterios_04` FOREIGN KEY (`criteria_id`) REFERENCES `criterias` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `criterias_users`
--
ALTER TABLE `criterias_users`
  ADD CONSTRAINT `usuario_criterios_01` FOREIGN KEY (`expert_user`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_criterios_02` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_criterios_03` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_criterios_04` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_criterios_05` FOREIGN KEY (`criteria_id`) REFERENCES `criterias` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_criterios_06` FOREIGN KEY (`quality_user_id`) REFERENCES `quality_users` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `documents`
--
ALTER TABLE `documents`
  ADD CONSTRAINT `documentos_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documentos_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documentos_03` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documentos_04` FOREIGN KEY (`repository_id`) REFERENCES `repositories` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `documentos_05` FOREIGN KEY (`document_state_id`) REFERENCES `document_states` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `document_states`
--
ALTER TABLE `document_states`
  ADD CONSTRAINT `estado_documentos_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `estado_documentos_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `modules`
--
ALTER TABLE `modules`
  ADD CONSTRAINT `modulos_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `modulos_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `payed_searches`
--
ALTER TABLE `payed_searches`
  ADD CONSTRAINT `busqueda_pagadas_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `busqueda_pagadas_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `busqueda_pagadas_03` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `privileges`
--
ALTER TABLE `privileges`
  ADD CONSTRAINT `privilegios_01` FOREIGN KEY (`permission_reading`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `privilegios_02` FOREIGN KEY (`permission_writing`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `privilegios_03` FOREIGN KEY (`permission_execution`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `privilegios_04` FOREIGN KEY (`permission_impresion`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `privilegios_05` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `privilegios_06` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `privilegios_07` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `privilegios_08` FOREIGN KEY (`submodule_id`) REFERENCES `submodules` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `quality_users`
--
ALTER TABLE `quality_users`
  ADD CONSTRAINT `calidad_usuarios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `calidad_usuarios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `repositories`
--
ALTER TABLE `repositories`
  ADD CONSTRAINT `repositorios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `repositorios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `repositorios_03` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `repositories_users`
--
ALTER TABLE `repositories_users`
  ADD CONSTRAINT `usuario_repositorios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_repositorios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_repositorios_03` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_repositorios_04` FOREIGN KEY (`repository_id`) REFERENCES `repositories` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `usuario_repositorios_05` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `repository_restrictions`
--
ALTER TABLE `repository_restrictions`
  ADD CONSTRAINT `repositorio_restriccions_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `repositorio_restriccions_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `repositorio_restriccions_03` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `repositorio_restriccions_04` FOREIGN KEY (`repository_id`) REFERENCES `repositories` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `search_criterias`
--
ALTER TABLE `search_criterias`
  ADD CONSTRAINT `busqueda_criterios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `busqueda_criterios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `busqueda_criterios_03` FOREIGN KEY (`payed_search_id`) REFERENCES `payed_searches` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `busqueda_criterios_04` FOREIGN KEY (`criteria_id`) REFERENCES `criterias` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `submodules`
--
ALTER TABLE `submodules`
  ADD CONSTRAINT `submodulos_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `submodulos_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `submodulos_03` FOREIGN KEY (`module_id`) REFERENCES `modules` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `users_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON UPDATE NO ACTION;

--
-- Filtros para la tabla `user_types`
--
ALTER TABLE `user_types`
  ADD CONSTRAINT `tipo_usuarios_01` FOREIGN KEY (`activation_id`) REFERENCES `activations` (`id`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `tipo_usuarios_02` FOREIGN KEY (`internalstate_id`) REFERENCES `internalstates` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
