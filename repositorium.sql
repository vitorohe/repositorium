SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `repositorium` ;
CREATE SCHEMA IF NOT EXISTS `repositorium` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `repositorium` ;

-- -----------------------------------------------------
-- Table `repositorium`.`internalstates`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`internalstates` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`internalstates` (
  `id` CHAR(1) NOT NULL COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB
COMMENT = 'Estados Internos de los Registros del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`activations`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`activations` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`activations` (
  `id` CHAR(1) NOT NULL COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Estado de Activacion' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  PRIMARY KEY (`id`) ,
  INDEX `activacions_01` (`internalstate_id` ASC) ,
  CONSTRAINT `activacions_01`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Estado de Activacion de los Registros del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`users` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`users` (
  `id` INT(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Usuario' ,
  `email` VARCHAR(150) NOT NULL COMMENT 'Correo Electronico del Usuario' ,
  `username` VARCHAR(50) NOT NULL COMMENT 'Nombre de Usuario en el Sistema' ,
  `password` VARCHAR(50) NOT NULL COMMENT 'Clave de Acceso en el Sistema' ,
  `salt` VARCHAR(100) NOT NULL ,
  `is_administrator` CHAR(1) NOT NULL COMMENT 'El usuario es Administrador del Sistema' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  PRIMARY KEY (`id`) ,
  INDEX `users_01` (`activation_id` ASC) ,
  INDEX `users_02` (`internalstate_id` ASC) ,
  INDEX `users_03` (`is_administrator` ASC) ,
  CONSTRAINT `users_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `users_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Usuarios del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`user_types`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`user_types` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`user_types` (
  `id` INT(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Tipo de Usuario' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  PRIMARY KEY (`id`) ,
  INDEX `tipo_usuarios_01` (`activation_id` ASC) ,
  INDEX `tipo_usuarios_02` (`internalstate_id` ASC) ,
  CONSTRAINT `tipo_usuarios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `tipo_usuarios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Tipos de Usuarios del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`modules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`modules` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`modules` (
  `id` INT(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Modulo del Sistema' ,
  `description` TEXT NOT NULL COMMENT 'Descripcion del Modulo del Sistema' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  PRIMARY KEY (`id`) ,
  INDEX `modulos_01` (`activation_id` ASC) ,
  INDEX `modulos_02` (`internalstate_id` ASC) ,
  CONSTRAINT `modulos_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `modulos_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Modulos del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`submodules`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`submodules` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`submodules` (
  `id` INT(4) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Submodulo' ,
  `description` TEXT NOT NULL COMMENT 'Descripcion del Submodulo' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `module_id` INT(2) NOT NULL COMMENT 'Clave Foranea Modulos' ,
  PRIMARY KEY (`id`) ,
  INDEX `submodulos_01` (`activation_id` ASC) ,
  INDEX `submodulos_02` (`internalstate_id` ASC) ,
  INDEX `submodulos_03` (`module_id` ASC) ,
  CONSTRAINT `submodulos_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `submodulos_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `submodulos_03`
    FOREIGN KEY (`module_id` )
    REFERENCES `repositorium`.`modules` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Submodulos del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`privileges`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`privileges` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`privileges` (
  `id` INT(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `permission_reading` CHAR(1) NOT NULL COMMENT 'Permiso de Lectura en el Submodulo' ,
  `permission_writing` CHAR(1) NOT NULL COMMENT 'Permiso de Escritura en el Submodulo' ,
  `permission_execution` CHAR(1) NOT NULL COMMENT 'Permiso de Ejecucion en el Submodulo' ,
  `permission_impresion` CHAR(1) NOT NULL COMMENT 'Permiso de Impresion en el Submodulo' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_type_id` INT(2) NOT NULL COMMENT 'Clave Foranea Tipo_Usuarios' ,
  `submodule_id` INT(4) NOT NULL COMMENT 'Clave Foranea Modulos' ,
  PRIMARY KEY (`id`) ,
  INDEX `privilegios_01` (`permission_reading` ASC) ,
  INDEX `privilegios_02` (`permission_writing` ASC) ,
  INDEX `privilegios_03` (`permission_execution` ASC) ,
  INDEX `privilegios_04` (`permission_impresion` ASC) ,
  INDEX `privilegios_05` (`activation_id` ASC) ,
  INDEX `privilegios_06` (`internalstate_id` ASC) ,
  INDEX `privilegios_07` (`user_type_id` ASC) ,
  INDEX `privilegios_08` (`submodule_id` ASC) ,
  CONSTRAINT `privilegios_01`
    FOREIGN KEY (`permission_reading` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `privilegios_02`
    FOREIGN KEY (`permission_writing` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `privilegios_03`
    FOREIGN KEY (`permission_execution` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `privilegios_04`
    FOREIGN KEY (`permission_impresion` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `privilegios_05`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `privilegios_06`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `privilegios_07`
    FOREIGN KEY (`user_type_id` )
    REFERENCES `repositorium`.`user_types` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `privilegios_08`
    FOREIGN KEY (`submodule_id` )
    REFERENCES `repositorium`.`submodules` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Privilegios de los Tipos de Usuarios en el Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`repositories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`repositories` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`repositories` (
  `id` INT(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Repositorio' ,
  `internal_name` VARCHAR(50) NOT NULL COMMENT 'Nombre Interno del Repositorio' ,
  `description` TEXT NULL COMMENT 'Descripcion del Repositorio' ,
  `created` DATETIME NOT NULL COMMENT 'Fecha de Creacion del Repositorio' ,
  `modifed` DATETIME NULL COMMENT 'Fecha de la Ultima Modificacion del Repositorio' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios' ,
  PRIMARY KEY (`id`) ,
  INDEX `repositorios_01` (`activation_id` ASC) ,
  INDEX `repositorios_02` (`internalstate_id` ASC) ,
  INDEX `repositorios_03` (`user_id` ASC) ,
  CONSTRAINT `repositorios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `repositorios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `repositorios_03`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Repositorio de Documentos del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`repositories_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`repositories_users` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`repositories_users` (
  `id` INT(12) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios' ,
  `repository_id` INT(6) NOT NULL COMMENT 'Clave Foranea Repositorios' ,
  `user_type_id` INT(2) NOT NULL COMMENT 'Clave Foranea Tipo_Usuarios' ,
  PRIMARY KEY (`id`) ,
  INDEX `usuario_repositorios_01` (`activation_id` ASC) ,
  INDEX `usuario_repositorios_02` (`internalstate_id` ASC) ,
  INDEX `usuario_repositorios_03` (`user_id` ASC) ,
  INDEX `usuario_repositorios_04` (`repository_id` ASC) ,
  INDEX `usuario_repositorios_05` (`user_type_id` ASC) ,
  CONSTRAINT `usuario_repositorios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_repositorios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_repositorios_03`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_repositorios_04`
    FOREIGN KEY (`repository_id` )
    REFERENCES `repositorium`.`repositories` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_repositorios_05`
    FOREIGN KEY (`user_type_id` )
    REFERENCES `repositorium`.`user_types` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `repositorium`.`criterias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`criterias` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`criterias` (
  `id` INT(6) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Criterio' ,
  `question` TEXT NOT NULL COMMENT 'Descripcion del Criterio' ,
  `upload_score` INT(4) NOT NULL COMMENT 'Puntaje para poder Subir un Documento' ,
  `download_score` INT(4) NOT NULL COMMENT 'Puntaje para poder Bajar un Documento' ,
  `collaboration_score` INT(4) NOT NULL COMMENT 'Puntaje que uno Logra al Compartir un Documento' ,
  `register_date` DATETIME NOT NULL COMMENT 'Fecha de Creacion del Criterio' ,
  `register_ip` VARCHAR(25) NOT NULL COMMENT 'IP de Creacion del Criterio' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios (Creador del Criterio)' ,
  PRIMARY KEY (`id`) ,
  INDEX `criterios_01` (`activation_id` ASC) ,
  INDEX `criterios_02` (`internalstate_id` ASC) ,
  INDEX `criterios_03` (`user_id` ASC) ,
  CONSTRAINT `criterios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `criterios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `criterios_03`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `repositorium`.`quality_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`quality_users` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`quality_users` (
  `id` INT(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre de la Calidad del Usuario (Nombre otorgado por la Participacion en el Sistema)' ,
  `description` TEXT NOT NULL COMMENT 'Descripcion de la Calidad del Usuario' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `percentage` INT(3) NULL COMMENT 'Porcentaje de Aceptacion de la Calidad de Usuario' ,
  PRIMARY KEY (`id`) ,
  INDEX `calidad_usuarios_01` (`activation_id` ASC) ,
  INDEX `calidad_usuarios_02` (`internalstate_id` ASC) ,
  CONSTRAINT `calidad_usuarios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `calidad_usuarios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `repositorium`.`criterias_users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`criterias_users` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`criterias_users` (
  `id` INT(12) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `successful_evaluation` INT(10) NOT NULL COMMENT 'Cantidad de Evaluacion Exitosas de Documentos' ,
  `negative_evaluation` INT(10) NOT NULL COMMENT 'Cantidad de Evaluacion Negativas de Documentos' ,
  `score_obtained` INT(10) NOT NULL COMMENT 'Puntaje del Usuario obtenido en el Criterio' ,
  `expert_user` CHAR(1) NOT NULL COMMENT 'Verificar si el Usuario es Experto o no en el Criterio' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios' ,
  `criteria_id` INT(6) NOT NULL COMMENT 'Clave Foranea Criterios' ,
  `quality_user_id` INT(2) NOT NULL COMMENT 'Clave Foranea Calidad_Usuarios' ,
  PRIMARY KEY (`id`) ,
  INDEX `usuario_criterios_01` (`expert_user` ASC) ,
  INDEX `usuario_criterios_02` (`activation_id` ASC) ,
  INDEX `usuario_criterios_03` (`internalstate_id` ASC) ,
  INDEX `usuario_criterios_04` (`user_id` ASC) ,
  INDEX `usuario_criterios_05` (`criteria_id` ASC) ,
  INDEX `usuario_criterios_06` (`quality_user_id` ASC) ,
  CONSTRAINT `usuario_criterios_01`
    FOREIGN KEY (`expert_user` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_criterios_02`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_criterios_03`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_criterios_04`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_criterios_05`
    FOREIGN KEY (`criteria_id` )
    REFERENCES `repositorium`.`criterias` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `usuario_criterios_06`
    FOREIGN KEY (`quality_user_id` )
    REFERENCES `repositorium`.`quality_users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Usuarios asociados a los Criterios para almacenar el Puntaje' /* comment truncated */ ;


-- -----------------------------------------------------
-- Table `repositorium`.`categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`categories` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`categories` (
  `id` INT(4) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre de la Categoria' ,
  `description` TEXT NOT NULL COMMENT 'Descripcion de la Categoria' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios (Creador de la Catergoria)' ,
  PRIMARY KEY (`id`) ,
  INDEX `categorias_01` (`activation_id` ASC) ,
  INDEX `categorias_02` (`internalstate_id` ASC) ,
  INDEX `categorias_03` (`user_id` ASC) ,
  CONSTRAINT `categorias_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `categorias_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `categorias_03`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Categorias del Sistema' ;


-- -----------------------------------------------------
-- Table `repositorium`.`category_criterias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`category_criterias` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`category_criterias` (
  `id` INT(10) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `category_id` INT(4) NOT NULL COMMENT 'Clave Foranea Categorias' ,
  `criteria_id` INT(6) NOT NULL COMMENT 'Clave Foranea Criterios' ,
  PRIMARY KEY (`id`) ,
  INDEX `categoria_criterios_01` (`activation_id` ASC) ,
  INDEX `categoria_criterios_02` (`internalstate_id` ASC) ,
  INDEX `categoria_criterios_03` (`category_id` ASC) ,
  INDEX `categoria_criterios_04` (`criteria_id` ASC) ,
  CONSTRAINT `categoria_criterios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `categoria_criterios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `categoria_criterios_03`
    FOREIGN KEY (`category_id` )
    REFERENCES `repositorium`.`categories` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `categoria_criterios_04`
    FOREIGN KEY (`criteria_id` )
    REFERENCES `repositorium`.`criterias` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Criterios que pertenecen a una Categoria' ;


-- -----------------------------------------------------
-- Table `repositorium`.`document_states`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`document_states` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`document_states` (
  `id` INT(2) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Estado del Documento' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  PRIMARY KEY (`id`) ,
  INDEX `estado_documentos_01` (`activation_id` ASC) ,
  INDEX `estado_documentos_02` (`internalstate_id` ASC) ,
  CONSTRAINT `estado_documentos_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `estado_documentos_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Estado de los Documentos del Repositorio' ;


-- -----------------------------------------------------
-- Table `repositorium`.`documents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`documents` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`documents` (
  `id` INT(15) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Documento' ,
  `description` TEXT NOT NULL COMMENT 'Descripcion del Documento' ,
  `register_date` DATETIME NOT NULL COMMENT 'Fecha de la Subida del Documento' ,
  `register_ip` VARCHAR(25) NOT NULL COMMENT 'IP de la Subida del Documento' ,
  `registration_date` DATETIME NOT NULL COMMENT 'Fecha del Alta del Documento' ,
  `registration_ip` VARCHAR(25) NOT NULL COMMENT 'IP del Alta del Documento' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios' ,
  `repository_id` INT(6) NOT NULL COMMENT 'Clave Foranea Repositorios' ,
  `document_state_id` INT(2) NOT NULL COMMENT 'Clave Foranea Estado_Documentos' ,
  PRIMARY KEY (`id`) ,
  INDEX `documentos_01` (`activation_id` ASC) ,
  INDEX `documentos_02` (`internalstate_id` ASC) ,
  INDEX `documentos_05` (`document_state_id` ASC) ,
  CONSTRAINT `documentos_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documentos_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documentos_03`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documentos_04`
    FOREIGN KEY (`repository_id` )
    REFERENCES `repositorium`.`repositories` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documentos_05`
    FOREIGN KEY (`document_state_id` )
    REFERENCES `repositorium`.`document_states` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Documentos subidos por los Usuarios al Repositorio' ;


-- -----------------------------------------------------
-- Table `repositorium`.`criterias_documents`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`criterias_documents` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`criterias_documents` (
  `id` INT(21) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `document_id` INT(15) NOT NULL COMMENT 'Clave Foranea Documentos' ,
  `criteria_id` INT(6) NOT NULL COMMENT 'Clave Foranea Criterios' ,
  PRIMARY KEY (`id`) ,
  INDEX `documento_criterios_01` (`activation_id` ASC) ,
  INDEX `documento_criterios_02` (`internalstate_id` ASC) ,
  INDEX `documento_criterios_03` (`document_id` ASC) ,
  INDEX `documento_criterios_04` (`criteria_id` ASC) ,
  CONSTRAINT `documento_criterios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documento_criterios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documento_criterios_03`
    FOREIGN KEY (`document_id` )
    REFERENCES `repositorium`.`documents` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documento_criterios_04`
    FOREIGN KEY (`criteria_id` )
    REFERENCES `repositorium`.`criterias` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `repositorium`.`payed_searches`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`payed_searches` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`payed_searches` (
  `id` INT(15) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `total_spent_points` INT(6) NOT NULL COMMENT 'Puntos Gastados en Total' ,
  `documents_amount` INT(4) NOT NULL COMMENT 'Cantidad de Documentos Solicitados' ,
  `register_date` DATETIME NOT NULL COMMENT 'Fecha de la Solicitud de Busqueda' ,
  `register_ip` VARCHAR(25) NOT NULL COMMENT 'IP de la Solicitud de Busqueda' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios' ,
  PRIMARY KEY (`id`) ,
  INDEX `busqueda_pagadas_01` (`activation_id` ASC) ,
  INDEX `busqueda_pagadas_02` (`internalstate_id` ASC) ,
  INDEX `busqueda_pagadas_03` (`user_id` ASC) ,
  CONSTRAINT `busqueda_pagadas_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `busqueda_pagadas_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `busqueda_pagadas_03`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB, 
COMMENT = 'Datos Generales de la Busqueda Pagada' ;


-- -----------------------------------------------------
-- Table `repositorium`.`search_criterias`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`search_criterias` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`search_criterias` (
  `id` INT(21) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `spent_points` INT(6) NOT NULL COMMENT 'Puntos Gastados por el Criterio' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `payed_search_id` INT(15) NOT NULL COMMENT 'Clave Foranea Busqueda_Pagadas' ,
  `criteria_id` INT(6) NOT NULL COMMENT 'Clave Foranea Criterios' ,
  PRIMARY KEY (`id`) ,
  INDEX `busqueda_criterios_01` (`activation_id` ASC) ,
  INDEX `busqueda_criterios_02` (`internalstate_id` ASC) ,
  INDEX `busqueda_criterios_03` (`payed_search_id` ASC) ,
  INDEX `busqueda_criterios_04` (`criteria_id` ASC) ,
  CONSTRAINT `busqueda_criterios_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `busqueda_criterios_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `busqueda_criterios_03`
    FOREIGN KEY (`payed_search_id` )
    REFERENCES `repositorium`.`payed_searches` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `busqueda_criterios_04`
    FOREIGN KEY (`criteria_id` )
    REFERENCES `repositorium`.`criterias` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `repositorium`.`attachfiles`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`attachfiles` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`attachfiles` (
  `id` INT(20) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre del Documento' ,
  `location` VARCHAR(100) NOT NULL COMMENT 'Ubicacion del Documento Adjunto en el Sistema' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `document_id` INT(15) NOT NULL COMMENT 'Clave Foranea Documentos' ,
  PRIMARY KEY (`id`) ,
  INDEX `documento_adjuntos_01` (`activation_id` ASC) ,
  INDEX `documento_adjuntos_02` (`internalstate_id` ASC) ,
  INDEX `documento_adjuntos_03` (`document_id` ASC) ,
  CONSTRAINT `documento_adjuntos_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documento_adjuntos_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `documento_adjuntos_03`
    FOREIGN KEY (`document_id` )
    REFERENCES `repositorium`.`documents` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `repositorium`.`repository_restrictions`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `repositorium`.`repository_restrictions` ;

CREATE  TABLE IF NOT EXISTS `repositorium`.`repository_restrictions` (
  `id` INT(8) NOT NULL AUTO_INCREMENT COMMENT 'Identificador' ,
  `name` VARCHAR(100) NOT NULL COMMENT 'Nombre de la Restriccion' ,
  `extension` VARCHAR(10) NOT NULL COMMENT 'Extension del Archivo que se Permite Subir al Repositorio' ,
  `seize` INT(10) NOT NULL COMMENT 'Peso Permitido del Archivo Adjunto' ,
  `amount` INT(5) NOT NULL COMMENT 'Cantidad de Archivos Adjuntos Permitidos' ,
  `register_date` DATETIME NOT NULL COMMENT 'Fecha de Creacion de la Restriccion' ,
  `register_ip` VARCHAR(25) NOT NULL COMMENT 'IP de Creacion de la Restriccion' ,
  `activation_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Activacions' ,
  `internalstate_id` CHAR(1) NOT NULL COMMENT 'Clave Foranea Estadointernos' ,
  `user_id` INT(6) NOT NULL COMMENT 'Clave Foranea Usuarios' ,
  `repository_id` INT(6) NOT NULL COMMENT 'Clave Foranea Repositorios' ,
  PRIMARY KEY (`id`) ,
  INDEX `repositorio_restriccions_01` (`activation_id` ASC) ,
  INDEX `repositorio_restriccions_02` (`internalstate_id` ASC) ,
  INDEX `repositorio_restriccions_03` (`user_id` ASC) ,
  INDEX `repositorio_restriccions_04` (`repository_id` ASC) ,
  CONSTRAINT `repositorio_restriccions_01`
    FOREIGN KEY (`activation_id` )
    REFERENCES `repositorium`.`activations` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `repositorio_restriccions_02`
    FOREIGN KEY (`internalstate_id` )
    REFERENCES `repositorium`.`internalstates` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `repositorio_restriccions_03`
    FOREIGN KEY (`user_id` )
    REFERENCES `repositorium`.`users` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION,
  CONSTRAINT `repositorio_restriccions_04`
    FOREIGN KEY (`repository_id` )
    REFERENCES `repositorium`.`repositories` (`id` )
    ON DELETE RESTRICT
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `repositorium`.`internalstates`
-- -----------------------------------------------------
START TRANSACTION;
USE `repositorium`;
INSERT INTO `repositorium`.`internalstates` (`id`, `name`) VALUES ('A', 'Activo');

COMMIT;

-- -----------------------------------------------------
-- Data for table `repositorium`.`activations`
-- -----------------------------------------------------
START TRANSACTION;
USE `repositorium`;
INSERT INTO `repositorium`.`activations` (`id`, `name`, `internalstate_id`) VALUES ('A', 'Activo', 'A');
INSERT INTO `repositorium`.`activations` (`id`, `name`, `internalstate_id`) VALUES ('N', 'No Activo', 'A');

COMMIT;

-- -----------------------------------------------------
-- Data for table `repositorium`.`users`
-- -----------------------------------------------------
START TRANSACTION;
USE `repositorium`;
INSERT INTO `repositorium`.`users` (`id`, `name`, `email`, `username`, `password`, `salt`, `is_administrator`, `activation_id`, `internalstate_id`) VALUES (1, 'admin', 'admin@example.com', 'admin', 'fbe82ab72970b9940724512227185348eac9d7fd', '1738993739', '1', 'A', 'A');

COMMIT;

-- -----------------------------------------------------
-- Data for table `repositorium`.`user_types`
-- -----------------------------------------------------
START TRANSACTION;
USE `repositorium`;
INSERT INTO `repositorium`.`user_types` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES (1, 'Administrador del Repositorio', 'A', 'A');
INSERT INTO `repositorium`.`user_types` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES (2, 'Usuario Normal', 'A', 'A');

COMMIT;

-- -----------------------------------------------------
-- Data for table `repositorium`.`document_states`
-- -----------------------------------------------------
START TRANSACTION;
USE `repositorium`;
INSERT INTO `repositorium`.`document_states` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES (1, 'Subido', 'A', 'A');
INSERT INTO `repositorium`.`document_states` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES (2, 'En proceso de evaluacion experta', 'A', 'A');
INSERT INTO `repositorium`.`document_states` (`id`, `name`, `activation_id`, `internalstate_id`) VALUES (3, 'Validado por experto', 'A', 'A');

COMMIT;
