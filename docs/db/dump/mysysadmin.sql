SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

DROP SCHEMA IF EXISTS `mysysadmin` ;
CREATE SCHEMA IF NOT EXISTS `mysysadmin` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
SHOW WARNINGS;
USE `mysysadmin` ;

-- -----------------------------------------------------
-- Table `mysysadmin`.`user`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`user` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`user` (
  `user_id` SMALLINT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `salt` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mysysadmin`.`role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`role` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`role` (
  `role_id` SMALLINT NOT NULL AUTO_INCREMENT,
  `code` VARCHAR(255) NOT NULL,
  `name` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` DATETIME NULL,
  PRIMARY KEY (`role_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mysysadmin`.`user_role`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`user_role` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`user_role` (
  `user_id` SMALLINT NOT NULL,
  `role_id` SMALLINT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX `user_role_id_idx` (`user_id` ASC),
  INDEX `user_role_role_1_idx` (`role_id` ASC),
  PRIMARY KEY (`user_id`, `role_id`),
  CONSTRAINT `user_role_user_1`
    FOREIGN KEY (`user_id`)
    REFERENCES `mysysadmin`.`user` (`user_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `user_role_role_1`
    FOREIGN KEY (`role_id`)
    REFERENCES `mysysadmin`.`role` (`role_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mysysadmin`.`server`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`server` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`server` (
  `server_id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `ip` VARCHAR(255) NOT NULL COMMENT 'IP of the server. e.g.: 20.25.000.001',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`server_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mysysadmin`.`domain`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`domain` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`domain` (
  `domain_id` INT NOT NULL AUTO_INCREMENT,
  `server_id` INT NOT NULL,
  `url` VARCHAR(255) NOT NULL,
  `root_path` VARCHAR(255) NULL COMMENT 'Directory where website has been placed',
  `host_conf_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`domain_id`),
  INDEX `fk_domain_server1_idx` (`server_id` ASC),
  CONSTRAINT `fk_domain_server1`
    FOREIGN KEY (`server_id`)
    REFERENCES `mysysadmin`.`server` (`server_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mysysadmin`.`user_account_status`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`user_account_status` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`user_account_status` (
  `user_account_status_id` TINYINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`user_account_status_id`))
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mysysadmin`.`user_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`user_account` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`user_account` (
  `user_account_id` INT NOT NULL,
  `server_id` INT NOT NULL,
  `user_account_status_id` TINYINT UNSIGNED NOT NULL,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `pem` TEXT NULL COMMENT 'This is a PEM formatted file containing just the private-key of a specific certificate. (e.g. .pem, .key, .cer, .cert, mor)',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_account_id`),
  INDEX `fk_user_account_server1_idx` (`server_id` ASC),
  INDEX `fk_user_account_user_account_status1_idx` (`user_account_status_id` ASC),
  CONSTRAINT `fk_user_server1`
    FOREIGN KEY (`server_id`)
    REFERENCES `mysysadmin`.`server` (`server_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_user_user_status1`
    FOREIGN KEY (`user_account_status_id`)
    REFERENCES `mysysadmin`.`user_account_status` (`user_account_status_id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

-- -----------------------------------------------------
-- Table `mysysadmin`.`domain_user_account`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `mysysadmin`.`domain_user_account` ;

SHOW WARNINGS;
CREATE TABLE IF NOT EXISTS `mysysadmin`.`domain_user_account` (
  `domain_user_account_id` INT NOT NULL AUTO_INCREMENT,
  `domain_id` INT NOT NULL,
  `user_account_id` INT NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`domain_user_account_id`),
  INDEX `fk_domain_user_domain1_idx` (`domain_id` ASC),
  INDEX `fk_domain_user_account_user1_idx` (`user_account_id` ASC),
  CONSTRAINT `fk_domain_user_account_domain1`
    FOREIGN KEY (`domain_id`)
    REFERENCES `mysysadmin`.`domain` (`domain_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_domain_user_account_user1`
    FOREIGN KEY (`user_account_id`)
    REFERENCES `mysysadmin`.`user_account` (`user_account_id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;

SHOW WARNINGS;

SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
