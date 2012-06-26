-- phpMyAdmin SQL Dump
-- version 3.4.10.1
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 26-06-2012 a las 01:52:27
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
  `extension` varchar(6) NOT NULL,
  `activation_id` char(1) NOT NULL COMMENT 'Clave Foranea Activacions',
  `internalstate_id` char(1) NOT NULL COMMENT 'Clave Foranea Estadointernos',
  `document_id` int(15) NOT NULL COMMENT 'Clave Foranea Documentos',
  PRIMARY KEY (`id`),
  KEY `documento_adjuntos_01` (`activation_id`),
  KEY `documento_adjuntos_02` (`internalstate_id`),
  KEY `documento_adjuntos_03` (`document_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Categorias del Sistema' AUTO_INCREMENT=3 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Criterios que pertenecen a una Categoria' AUTO_INCREMENT=6 ;

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
  `questions_quantity` int(6) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `criterios_01` (`activation_id`),
  KEY `criterios_02` (`internalstate_id`),
  KEY `criterios_03` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=82 ;

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
  `yes_eval` int(6) NOT NULL DEFAULT '0',
  `no_eval` int(6) NOT NULL DEFAULT '0',
  `total_eval` int(7) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `documento_criterios_01` (`activation_id`),
  KEY `documento_criterios_02` (`internalstate_id`),
  KEY `documento_criterios_03` (`document_id`),
  KEY `documento_criterios_04` (`criteria_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=89 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Usuarios asociados a los Criterios para almacenar el Puntaje' AUTO_INCREMENT=55 ;

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
  `validation_date` datetime NOT NULL COMMENT 'Fecha del Alta del Documento',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Documentos subidos por los Usuarios al Repositorio' AUTO_INCREMENT=140 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Repositorio de Documentos del Sistema' AUTO_INCREMENT=52 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27 ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `repository_restrictions`
--

CREATE TABLE IF NOT EXISTS `repository_restrictions` (
  `id` int(8) NOT NULL AUTO_INCREMENT COMMENT 'Identificador',
  `name` varchar(100) NOT NULL COMMENT 'Nombre de la Restriccion',
  `extension` varchar(100) NOT NULL COMMENT 'Extension del Archivo que se Permite Subir al Repositorio',
  `size` int(10) NOT NULL COMMENT 'Peso Permitido del Archivo Adjunto',
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Usuarios del Sistema' AUTO_INCREMENT=21 ;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`, `salt`, `is_administrator`, `activation_id`, `internalstate_id`) VALUES
(1, 'Superman', 'super@admin.com', 'superadmin', 'c61aff6f3eefb6f02e153656e98c85719607a9ab', '972881407', '1', 'A', 'A'),
(2, 'Jhon Doe', 'user@example.com', 'johndoe', 'c4c257a92459ea0d62e8f07dcdd3ab7e39dd618e', '1424764266', '0', 'A', 'A');

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
