-- phpMyAdmin SQL Dump
-- version 3.4.5
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 07-05-2012 a las 23:04:28
-- Versión del servidor: 5.5.16
-- Versión de PHP: 5.3.8

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=37 ;

--
-- Volcado de datos para la tabla `criterias`
--

INSERT INTO `criterias` (`id`, `name`, `question`, `upload_score`, `download_score`, `collaboration_score`, `register_date`, `register_ip`, `activation_id`, `internalstate_id`, `user_id`) VALUES
(7, 'Buena redacciÃ³n', 'Â¿La conexiÃ³n de las ideas es buena?', 4, 3, 2, '0000-00-00 00:00:00', '', 'A', 'A', 1),
(8, 'aaaaa', 'asd', 2, 1, 3, '0000-00-00 00:00:00', '', 'A', 'A', 1),
(9, 'hola', 'hola', 2, 1, 3, '0000-00-00 00:00:00', '', 'A', 'A', 1),
(15, 'mycrit', 'mycrit', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(16, 'sdfbgfsdgd', 'gdsgfsd', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(17, 'hola', 'chao', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(18, 'sdafsa', 'fasfsa', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(19, 'sdafsa12', 'fasfsa12', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(20, 'soyuncrit', 'sadgds', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(21, 'gdsfsagda', 'fsagsafg', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(22, 'hola12', 'sdafs', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(23, 'hola12asdf', 'sdafsdswa', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(31, 'HOLA123', 'CHAO!1', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(32, 'mycrit1', 'asd??', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(33, 'MNBV', 'POLIKJ', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 10),
(34, 'sdgfda', 'sdgdsf', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 11),
(35, 'safsa', 'fsags', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 11),
(36, 'hjola', 'gholadfgs', 5, 10, 5, '0000-00-00 00:00:00', '', 'A', 'A', 11);

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
  `answer` int(2) NOT NULL DEFAULT '3' COMMENT 'Cumple o no el criterio',
  PRIMARY KEY (`id`),
  KEY `documento_criterios_01` (`activation_id`),
  KEY `documento_criterios_02` (`internalstate_id`),
  KEY `documento_criterios_03` (`document_id`),
  KEY `documento_criterios_04` (`criteria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=28 ;

--
-- Volcado de datos para la tabla `criterias_documents`
--

INSERT INTO `criterias_documents` (`id`, `activation_id`, `internalstate_id`, `document_id`, `criteria_id`, `answer`) VALUES
(1, 'A', 'A', 21, 7, 3),
(2, 'A', 'A', 23, 7, 3),
(3, 'A', 'A', 24, 7, 3),
(4, 'A', 'A', 25, 9, 3),
(5, 'A', 'A', 25, 7, 3),
(6, 'A', 'A', 26, 7, 3),
(7, 'A', 'A', 27, 7, 3),
(8, 'A', 'A', 28, 9, 3),
(9, 'A', 'A', 29, 8, 3),
(10, 'A', 'A', 30, 8, 3),
(11, 'A', 'A', 31, 7, 3),
(12, 'A', 'A', 32, 8, 3),
(13, 'A', 'A', 33, 8, 3),
(14, 'A', 'A', 34, 8, 3),
(15, 'A', 'A', 35, 7, 3),
(16, 'A', 'A', 35, 9, 3),
(17, 'A', 'A', 35, 8, 3),
(18, 'A', 'A', 36, 9, 3),
(19, 'A', 'A', 37, 33, 2),
(20, 'A', 'A', 38, 32, 1),
(21, 'A', 'A', 38, 31, 2),
(22, 'A', 'A', 39, 33, 1),
(23, 'A', 'A', 39, 32, 2),
(24, 'A', 'A', 39, 31, 1),
(25, 'A', 'A', 40, 32, 2),
(26, 'A', 'A', 41, 33, 1),
(27, 'A', 'A', 41, 31, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `criterias_users`
--

CREATE TABLE IF NOT EXISTS `criterias_users` (
  `id` int(12) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `successful_evaluation` int(10) NOT NULL COMMENT 'Cantidad de Evaluacion Exitosas de Documentos',
  `negative_evaluation` int(10) NOT NULL COMMENT 'Cantidad de Evaluacion Negativas de Documentos',
  `score_obtained` int(10) NOT NULL COMMENT 'Puntaje del Usuario obtenido en el Criterio',
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `user_id` int(6) NOT NULL COMMENT 'Clave Foranea Usuarios',
  `criteria_id` int(6) NOT NULL COMMENT 'Clave Foranea Criterios',
  `quality_user_id` int(2) NOT NULL COMMENT 'Clave Foranea Calidad_Usuarios',
  PRIMARY KEY (`id`),
  KEY `usuario_criterios_02` (`activation_id`),
  KEY `usuario_criterios_03` (`internalstate_id`),
  KEY `usuario_criterios_04` (`user_id`),
  KEY `usuario_criterios_05` (`criteria_id`),
  KEY `usuario_criterios_06` (`quality_user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Usuarios asociados a los Criterios para almacenar el Puntaje' AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `criterias_users`
--

INSERT INTO `criterias_users` (`id`, `successful_evaluation`, `negative_evaluation`, `score_obtained`, `activation_id`, `internalstate_id`, `user_id`, `criteria_id`, `quality_user_id`) VALUES
(2, 0, 0, 5, 'A', 'A', 10, 31, 1),
(3, 0, 0, 0, 'A', 'A', 10, 32, 1),
(4, 0, 0, 0, 'A', 'A', 10, 33, 1),
(5, 0, 0, 0, 'A', 'A', 11, 34, 1),
(6, 0, 0, 0, 'A', 'A', 11, 35, 1),
(7, 0, 0, 0, 'A', 'A', 11, 36, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Documentos subidos por los Usuarios al Repositorio' AUTO_INCREMENT=42 ;

--
-- Volcado de datos para la tabla `documents`
--

INSERT INTO `documents` (`id`, `name`, `description`, `register_date`, `register_ip`, `registration_date`, `registration_ip`, `activation_id`, `internalstate_id`, `user_id`, `repository_id`, `document_state_id`) VALUES
(13, 'assaf', 'sadsfa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 8, 2),
(14, 'asd', 'asdfsdg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 8, 2),
(15, 'dasfgsa', 'asfsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 8, 2),
(16, 'hola', 'chao', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 8, 2),
(17, 'safgsag', 'safasg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(18, 'dsgds', 'gsdhes', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(19, 'asd', 'hjhjjh', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(20, 'dshds', 'gdsh', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(21, 'asaf', 'asfsagsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(22, 'sadgsa', 'gsafasg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(23, 'sdfgsad', 'gsdhds', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(24, 'gdshdsggs', 'gsdgdsgsdgsd', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 10, 1),
(25, 'asdags', 'gadgdafdag', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 8, 1),
(26, 'safsag', 'sagsagsg', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 10, 8, 1),
(27, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(28, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(29, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(30, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(31, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(32, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(33, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(34, 'sdafsa', 'asfdfgsa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(35, 'asdsaf', 'sadgas', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(36, 'asdsaf', 'sadgas', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 1, 13, 1),
(37, 'asdasf', 'fasgsafag', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 10, 21, 1),
(38, 'adsgasfasg', 'safsagsagsaas', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 10, 21, 1),
(39, 'sdgasdfg', 'sgsafa', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 10, 21, 1),
(40, 'fshqwqwqq', 'qqwweret', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 10, 21, 1),
(41, 'ffsdxvcmvgf', 'sdfhzsgh', '0000-00-00 00:00:00', '', '0000-00-00 00:00:00', '', 'A', 'A', 10, 21, 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Estado de los Documentos del Repositorio' AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `document_states`
--

INSERT INTO `document_states` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES
(1, 'Subido', 'A', 'A'),
(2, 'En proceso de evaluacion experta', 'A', 'A'),
(3, 'Validado por experto', 'A', 'A'),
(4, 'Invalidado por experto', 'A', 'A');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Volcado de datos para la tabla `quality_users`
--

INSERT INTO `quality_users` (`id`, `name`, `description`, `activation_id`, `internalstate_id`, `percentage`) VALUES
(1, 'Usuario Experto', 'El usuario es un experto del criterio asociado.', 'A', 'A', NULL),
(2, 'Usuario Normal', 'Un usuario que ha evaluado un documento con un criterio asociado', 'A', 'A', NULL);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Repositorio de Documentos del Sistema' AUTO_INCREMENT=23 ;

--
-- Volcado de datos para la tabla `repositories`
--

INSERT INTO `repositories` (`id`, `name`, `internal_name`, `description`, `created`, `modifed`, `activation_id`, `internalstate_id`, `user_id`) VALUES
(8, 'example', 'example', 'esto es un example', '2012-04-30 02:42:16', NULL, 'A', 'A', 1),
(10, 'example1', 'example1', 'sdafsa', '2012-05-01 03:35:36', NULL, 'A', 'A', 1),
(11, 'example2', 'example2', 'sadfsa', '2012-05-05 02:44:57', NULL, 'A', 'A', 1),
(12, 'sdafsa', 'asd', 'asasf', '2012-05-05 22:37:13', NULL, 'A', 'A', 1),
(13, 'fsdhs', 'sdf', 'gasgha', '2012-05-05 22:37:24', NULL, 'A', 'A', 1),
(14, 'asdf', 'as12', 'sdgsdh', '2012-05-05 22:37:41', NULL, 'A', 'A', 1),
(15, 'asdf12', 'assagsa', 'safag', '2012-05-05 23:04:53', NULL, 'A', 'A', 10),
(16, '12', '2233', '1233', '2012-05-05 23:15:44', NULL, 'A', 'A', 1),
(17, '12122', '22331', '1233', '2012-05-05 23:19:42', NULL, 'A', 'A', 1),
(21, 'myrepo1', 'myrepo1', 'myrepo1', '2012-05-07 02:10:46', NULL, 'A', 'A', 10),
(22, 'dsfasg', 'sadsag', 'asdgsa', '2012-05-07 23:02:28', NULL, 'A', 'A', 11);

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
  `watching` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `usuario_repositorios_01` (`activation_id`),
  KEY `usuario_repositorios_02` (`internalstate_id`),
  KEY `usuario_repositorios_03` (`user_id`),
  KEY `usuario_repositorios_04` (`repository_id`),
  KEY `usuario_repositorios_05` (`user_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `repositories_users`
--

INSERT INTO `repositories_users` (`id`, `activation_id`, `internalstate_id`, `user_id`, `repository_id`, `user_type_id`, `watching`) VALUES
(1, 'N', 'A', 10, 11, 2, 0),
(4, 'A', 'A', 10, 10, 2, 0),
(6, 'A', 'A', 10, 21, 1, 0),
(7, 'A', 'A', 11, 22, 1, 0);

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Usuarios del Sistema' AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `salt`, `is_administrator`, `activation_id`, `internalstate_id`) VALUES
(1, 'hola', 'admin@example.com', 'admin', 'f62cb1c950869bd7e5c0fa7aff9292cf5e868856', '979830690', '1', 'A', 'A'),
(5, 'asd', 'asdf@asdf.com', 'adminas', 'fbeb768f39f4487d2815a92ba9a9ac25913d1434', '395944676', '', 'A', 'A'),
(7, 'qwerre', 'jkkj@jkjk.com', 'lkmnjn', 'addf20db054ee4fcd081d5f065f3bec11699e434', '1837267016', '0', 'A', 'A'),
(8, 'yuirih', 'gh@hg.com', 'mnbv', 'd637b8f63a25286463de97a92d2a3f80263d10b5', '1022373586', '1', 'A', 'A'),
(10, 'HOLA', 'admin@example1.com', 'fdhhdg', '5e69d71643e55ddc1d933a77d75e40b624cea8c3', '1652522636', '0', 'A', 'A'),
(11, 'safsa', 'sda@dsaf.com', 'sadfas', 'd101f43845c0a4c8befe60a2657f1957b7570bf4', '1135013228', '0', 'A', 'A');

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
