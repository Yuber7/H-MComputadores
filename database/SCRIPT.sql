-- MySQL Workbench Synchronization
-- Generated: 2020-10-29 19:03
-- Model: New Model
-- Version: 1.0
-- Project: Name of the project
-- Author: usuario

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

CREATE SCHEMA IF NOT EXISTS `h&mcomputadores` DEFAULT CHARACTER SET utf8 ;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`personas` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `apellido` VARCHAR(80) NOT NULL,
  `tipo_documento` ENUM("CC", "CE", "TI", "NIT", "REG", "PAS") NOT NULL,
  `documento` BIGINT(19) UNSIGNED NOT NULL,
  `telefono` BIGINT(20) NOT NULL,
  `rol` ENUM("Adminisitrador", "Proveedor", "Cliente") NOT NULL,
  `municipio_id` BIGINT(19) UNSIGNED NOT NULL,
  `direccion` VARCHAR(80) NOT NULL,
  `email` VARCHAR(320) NOT NULL,
  `password` VARCHAR(255) NULL DEFAULT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  UNIQUE INDEX `documento_UNIQUE` (`documento` ASC) ,
  INDEX `fk_usuario_municipio1_idx` (`municipio_id` ASC) ,
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) ,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `municipio_id_UNIQUE` (`municipio_id` ASC) ,
  CONSTRAINT `fk_usuario_municipio1`
    FOREIGN KEY (`municipio_id`)
    REFERENCES `h&mcomputadores`.`municipios` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`productos` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(254) NOT NULL,
  `marca` VARCHAR(45) NOT NULL,
  `categoria_id` BIGINT(19) UNSIGNED NOT NULL,
  `referencia_fabrica` VARCHAR(60) NULL DEFAULT NULL,
  `descripcion` TEXT NOT NULL,
  `stock` INT(10) UNSIGNED NOT NULL,
  `precio` DOUBLE NOT NULL,
  `estado` ENUM("Disponible", "Agotado") NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_producto_categoria1_idx` (`categoria_id` ASC) ,
  CONSTRAINT `fk_producto_categoria1`
    FOREIGN KEY (`categoria_id`)
    REFERENCES `h&mcomputadores`.`categorias` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`ventas` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `valor_total` DOUBLE UNSIGNED NOT NULL,
  `persona_id` BIGINT(19) UNSIGNED NOT NULL,
  `forma_pago` ENUM('Efectivo', 'Cheque', 'Otros') NOT NULL,
  `estado` ENUM("Pendiente", "Procesada") NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_venta_usuario2_idx` (`persona_id` ASC) ,
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  CONSTRAINT `fk_venta_usuario2`
    FOREIGN KEY (`persona_id`)
    REFERENCES `h&mcomputadores`.`personas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`detalle_ventas` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `valor_unitario` DOUBLE UNSIGNED NOT NULL,
  `cantidad` INT(10) UNSIGNED NOT NULL,
  `producto_id` BIGINT(19) UNSIGNED NOT NULL,
  `venta_id` BIGINT(19) UNSIGNED NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_detalleVenta_producto1_idx` (`producto_id` ASC) ,
  INDEX `fk_detalleVenta_venta1_idx` (`venta_id` ASC) ,
  CONSTRAINT `fk_detalleVenta_producto1`
    FOREIGN KEY (`producto_id`)
    REFERENCES `h&mcomputadores`.`productos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleVenta_venta1`
    FOREIGN KEY (`venta_id`)
    REFERENCES `h&mcomputadores`.`ventas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`compras` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `fecha` DATETIME NOT NULL,
  `valor_total` DOUBLE UNSIGNED NOT NULL,
  `persona_id` BIGINT(19) UNSIGNED NOT NULL,
  `estado` ENUM("Pendiente", "Recibida") NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_compra_usuario1_idx` (`persona_id` ASC) ,
  CONSTRAINT `fk_compra_usuario1`
    FOREIGN KEY (`persona_id`)
    REFERENCES `h&mcomputadores`.`personas` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`detalle_compras` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `valor_unitario` DOUBLE NOT NULL,
  `cantidad` MEDIUMINT(9) NOT NULL,
  `producto_id` BIGINT(19) UNSIGNED NOT NULL,
  `compra_id` BIGINT(19) UNSIGNED NOT NULL,
  `estado` ENUM("Pendiente", "Procesado") NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_detalleCompra_producto1_idx` (`producto_id` ASC) ,
  INDEX `fk_detalleCompra_compra1_idx` (`compra_id` ASC) ,
  CONSTRAINT `fk_detalleCompra_producto1`
    FOREIGN KEY (`producto_id`)
    REFERENCES `h&mcomputadores`.`productos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_detalleCompra_compra1`
    FOREIGN KEY (`compra_id`)
    REFERENCES `h&mcomputadores`.`compras` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`categorias` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(80) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`departamentos` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(90) NOT NULL,
  `region` ENUM("Caribe", "Centro Oriente", "Centro Sur", "Eje Cafetero - Antioquia", "Llano", "Pacífico") NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) )
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`municipios` (
  `id` BIGINT(19) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(90) NOT NULL,
  `departamento_id` BIGINT(19) UNSIGNED NOT NULL,
  `acortado` VARCHAR(40) NULL DEFAULT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_municipio_departamento1_idx` (`departamento_id` ASC) ,
  CONSTRAINT `fk_municipio_departamento1`
    FOREIGN KEY (`departamento_id`)
    REFERENCES `h&mcomputadores`.`departamentos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `h&mcomputadores`.`fotos` (
  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `descripcion` VARCHAR(150) NOT NULL,
  `ruta` VARCHAR(150) NOT NULL,
  `estado` ENUM("Activo", "Inactivo") NOT NULL,
  `productos_id` BIGINT(19) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC) ,
  INDEX `fk_fotos_productos1_idx` (`productos_id` ASC) ,
  CONSTRAINT `fk_fotos_productos1`
    FOREIGN KEY (`productos_id`)
    REFERENCES `h&mcomputadores`.`productos` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
