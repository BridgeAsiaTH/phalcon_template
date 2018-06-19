-- MySQL Script generated by MySQL Workbench
-- Mon Jun 18 22:56:25 2018
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema mainDb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Table `config`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `config` (
  `key` VARCHAR(100) NOT NULL,
  `value` VARCHAR(1000) NULL,
  `createdAt` TIMESTAMP NULL,
  `updatedAt` TIMESTAMP NULL,
  PRIMARY KEY (`key`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `owner`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `owner` (
  `id` VARCHAR(50) NOT NULL COMMENT 'Company code or some unique key',
  `name` VARCHAR(300) NOT NULL COMMENT 'Name of a person / Company name',
  `username` VARCHAR(50) NOT NULL COMMENT 'Name of a person / Company name',
  `password` VARCHAR(80) NOT NULL COMMENT 'Name of a person / Company name',
  `email` VARCHAR(80) NOT NULL COMMENT 'Name of a person / Company name',
  `metaDataJson` TEXT NOT NULL DEFAULT '{}',
  `createdAt` TIMESTAMP NULL,
  `updatedAt` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;

CREATE UNIQUE INDEX `username_UNIQUE` ON `owner` (`username` ASC);


-- -----------------------------------------------------
-- Table `brand`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `brand` (
  `id` VARCHAR(50) NOT NULL,
  `name` VARCHAR(300) NOT NULL,
  `ownerId` VARCHAR(45) NOT NULL,
  `metaDataJson` TEXT NOT NULL DEFAULT '{}',
  `createdAt` TIMESTAMP NULL,
  `updatedAt` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `OwnerBrand`
    FOREIGN KEY (`ownerId`)
    REFERENCES `owner` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `OwnerBrand_idx` ON `brand` (`ownerId` ASC);


-- -----------------------------------------------------
-- Table `campaign`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `campaign` (
  `id` VARCHAR(50) NOT NULL,
  `name` VARCHAR(300) NOT NULL,
  `ownerId` VARCHAR(45) NOT NULL,
  `metaDataJson` TEXT NOT NULL DEFAULT '{}',
  `status` ENUM('new', 'started', 'end', 'pause') NOT NULL DEFAULT 'new',
  `startAt` TIMESTAMP NULL,
  `endAt` TIMESTAMP NULL,
  `startedAt` TIMESTAMP NULL,
  `createdAt` TIMESTAMP NULL,
  `updatedAt` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `ownerCampaign`
    FOREIGN KEY (`ownerId`)
    REFERENCES `owner` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `ownerBrand_idx` ON `campaign` (`ownerId` ASC);


-- -----------------------------------------------------
-- Table `product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product` (
  `id` VARCHAR(50) NOT NULL,
  `name` VARCHAR(300) NOT NULL,
  `description` TEXT NULL,
  `metaDataJson` TEXT NOT NULL DEFAULT '{}',
  `status` ENUM('active', 'inactive', 'out-of-stock') NOT NULL DEFAULT 'active',
  `activeAt` TIMESTAMP NULL,
  `inactiveAt` TIMESTAMP NULL,
  `outOfStockAt` TIMESTAMP NULL,
  `createdAt` TIMESTAMP NULL,
  `updatedAt` TIMESTAMP NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `productSku`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `productSku` (
  `id` VARCHAR(50) NOT NULL,
  `brandProductCode` VARCHAR(50) NOT NULL COMMENT 'Reference product code from brand',
  `brandId` VARCHAR(50) NOT NULL,
  `productId` VARCHAR(50) NOT NULL,
  `metaDataJson` TEXT NOT NULL DEFAULT '{}' COMMENT 'Contain colour / size etc.',
  `startingStock` INT NOT NULL DEFAULT 0,
  `remainingStock` INT NOT NULL DEFAULT 0,
  `status` ENUM('active', 'inactive', 'out-of-stock') NOT NULL DEFAULT 'active',
  `activeAt` TIMESTAMP NULL,
  `inactiveAt` TIMESTAMP NULL,
  `outOfStockAt` TIMESTAMP NULL,
  `createdAt` TIMESTAMP NULL,
  `updatedAt` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `brandProductSku`
    FOREIGN KEY (`brandId`)
    REFERENCES `brand` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `productProductSku`
    FOREIGN KEY (`productId`)
    REFERENCES `product` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;

CREATE INDEX `brandProduct_idx` ON `productSku` (`brandId` ASC);

CREATE INDEX `productProductSku_idx` ON `productSku` (`productId` ASC);


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
