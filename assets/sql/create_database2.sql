-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema CSY2028_13430492
-- -----------------------------------------------------
DROP SCHEMA IF EXISTS `CSY2028_13430492` ;

-- -----------------------------------------------------
-- Schema CSY2028_13430492
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `CSY2028_13430492` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;
USE `CSY2028_13430492` ;

-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Seminar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Seminar` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Seminar` (
  `seminarId` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(45) NOT NULL,
  `startTime` DATETIME NOT NULL,
  `endTime` DATETIME NOT NULL,
  `description` VARCHAR(256) NOT NULL,
  `placesAvailable` INT NOT NULL,
  PRIMARY KEY (`seminarId`))
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Attendee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Attendee` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Attendee` (
  `attendeeId` INT NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `institution` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`attendeeId`))
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Room`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Room` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Room` (
  `roomId` INT NOT NULL AUTO_INCREMENT,
  `roomName` VARCHAR(20) NOT NULL,
  `capacity` INT NOT NULL,
  `location` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`roomId`))
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Attendee_attends_Seminar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Attendee_attends_Seminar` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Attendee_attends_Seminar` (
  `ticketId` INT NOT NULL AUTO_INCREMENT,
  `attendeeId` INT NOT NULL,
  `seminarId` INT NOT NULL,
  PRIMARY KEY (`ticketId`),
  INDEX `fk_Attendee_has_Seminar_Seminar1_idx` (`seminarId` ASC),
  INDEX `fk_Attendee_has_Seminar_Attendee_idx` (`attendeeId` ASC),
  CONSTRAINT `fk_Attendee_has_Seminar_Attendee`
  FOREIGN KEY (`attendeeId`)
  REFERENCES `CSY2028_13430492`.`Attendee` (`attendeeId`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Attendee_has_Seminar_Seminar1`
  FOREIGN KEY (`seminarId`)
  REFERENCES `CSY2028_13430492`.`Seminar` (`seminarId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Speaker`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Speaker` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Speaker` (
  `speakerId` INT NOT NULL AUTO_INCREMENT,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `address` VARCHAR(45) NOT NULL,
  `institution` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`speakerId`))
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Seminar_has_Speaker`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Seminar_has_Speaker` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Seminar_has_Speaker` (
  `speakerId` INT NOT NULL,
  `seminarId` INT NOT NULL,
  PRIMARY KEY (`speakerId`, `seminarId`),
  INDEX `fk_Speaker_has_Seminar_Seminar1_idx` (`seminarId` ASC),
  INDEX `fk_Speaker_has_Seminar_Speaker1_idx` (`speakerId` ASC),
  CONSTRAINT `fk_Speaker_has_Seminar_Speaker1`
  FOREIGN KEY (`speakerId`)
  REFERENCES `CSY2028_13430492`.`Speaker` (`speakerId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Speaker_has_Seminar_Seminar1`
  FOREIGN KEY (`seminarId`)
  REFERENCES `CSY2028_13430492`.`Seminar` (`seminarId`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Seminar_Organiser`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Seminar_Organiser` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Seminar_Organiser` (
  `login_name` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`login_name`))
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Seminar_Organiser_has_Seminar`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Seminar_Organiser_has_Seminar` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Seminar_Organiser_has_Seminar` (
  `login_name` VARCHAR(45) NOT NULL,
  `seminarId` INT NOT NULL,
  PRIMARY KEY (`login_name`, `seminarId`),
  INDEX `fk_Seminar_Organiser_has_Seminar_Seminar1_idx` (`seminarId` ASC),
  CONSTRAINT `fk_Seminar_Organiser_has_Seminar_Seminar1`
  FOREIGN KEY (`seminarId`)
  REFERENCES `CSY2028_13430492`.`Seminar` (`seminarId`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_Seminar_Organiser_has_Seminar_Seminar_Organiser1`
  FOREIGN KEY (`login_name`)
  REFERENCES `CSY2028_13430492`.`Seminar_Organiser` (`login_name`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Allocated_Room`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Allocated_Room` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Allocated_Room` (
  `seminarId` INT NOT NULL,
  `roomId` INT NOT NULL,
  PRIMARY KEY (`seminarId`, `roomId`),
  INDEX `fk_Room_has_Seminar_Seminar1_idx` (`seminarId` ASC),
  INDEX `fk_Room_has_Seminar_Room1_idx` (`roomId` ASC),
  CONSTRAINT `fk_Room_has_Seminar_Room1`
  FOREIGN KEY (`roomId`)
  REFERENCES `CSY2028_13430492`.`Room` (`roomId`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Room_has_Seminar_Seminar1`
  FOREIGN KEY (`seminarId`)
  REFERENCES `CSY2028_13430492`.`Seminar` (`seminarId`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
  ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `CSY2028_13430492`.`Centre_Management`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `CSY2028_13430492`.`Centre_Management` ;

CREATE TABLE IF NOT EXISTS `CSY2028_13430492`.`Centre_Management` (
  `login` VARCHAR(45) NOT NULL,
  `password` VARCHAR(45) NOT NULL,
  `firstName` VARCHAR(45) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`login`))
  ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
