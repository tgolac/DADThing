-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema events
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema events
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `events` DEFAULT CHARACTER SET utf8 ;
USE `events` ;

-- -----------------------------------------------------
-- Table `events`.`time`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`time` ;

CREATE TABLE IF NOT EXISTS `events`.`time` (
  `time_id` INT NOT NULL,
  `time` TIME NOT NULL,
  PRIMARY KEY (`time_id`));


-- -----------------------------------------------------
-- Table `events`.`campus`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`campus` ;

CREATE TABLE IF NOT EXISTS `events`.`campus` (
  `campus_id` INT NOT NULL,
  `campus_name` VARCHAR(255) NOT NULL,
  `campus_shortname` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`campus_id`));


-- -----------------------------------------------------
-- Table `events`.`room`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`room` ;

CREATE TABLE IF NOT EXISTS `events`.`room` (
  `room_id` INT NOT NULL,
  `campus_id` INT NOT NULL,
  `room_name` VARCHAR(25) NOT NULL,
  `room_capacity` INT NULL,
  `room_videolink` TINYINT(1) NULL,
  PRIMARY KEY (`room_id`),
  INDEX `room_campus_idx` (`campus_id` ASC),
  CONSTRAINT `room_campus`
    FOREIGN KEY (`campus_id`)
    REFERENCES `events`.`campus` (`campus_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `events`.`day`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`day` ;

CREATE TABLE IF NOT EXISTS `events`.`day` (
  `day_id` INT NOT NULL,
  `day_name` VARCHAR(25) NOT NULL,
  PRIMARY KEY (`day_id`));


-- -----------------------------------------------------
-- Table `events`.`audiencetype`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`audiencetype` ;

CREATE TABLE IF NOT EXISTS `events`.`audiencetype` (
  `audiencetype_id` INT NOT NULL,
  `audiencetype_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`audiencetype_id`));


-- -----------------------------------------------------
-- Table `events`.`event`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`event` ;

CREATE TABLE IF NOT EXISTS `events`.`event` (
  `event_id` INT NOT NULL,
  `event_name` VARCHAR(255) NOT NULL,
  `time_id_start` INT NOT NULL,
  `time_id_end` INT NOT NULL,
  `room_id` INT NOT NULL,
  `day_id` INT NOT NULL,
  `video_link` TINYINT(1) NULL,
  `event_description` TEXT NULL,
  `event_audiencetype_id` INT NOT NULL,
  PRIMARY KEY (`event_id`),
  INDEX `schedule_time_idx` (`time_id_start` ASC),
  INDEX `schedule_room_idx` (`room_id` ASC),
  INDEX `schedule_day_idx` (`day_id` ASC),
  INDEX `schedule_time_end_idx` (`time_id_end` ASC),
  INDEX `event_audiencetype_idx` (`event_audiencetype_id` ASC),
  CONSTRAINT `event_time_start`
    FOREIGN KEY (`time_id_start`)
    REFERENCES `events`.`time` (`time_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `event_room`
    FOREIGN KEY (`room_id`)
    REFERENCES `events`.`room` (`room_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `event_day`
    FOREIGN KEY (`day_id`)
    REFERENCES `events`.`day` (`day_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `event_time_end`
    FOREIGN KEY (`time_id_end`)
    REFERENCES `events`.`time` (`time_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `event_audiencetype`
    FOREIGN KEY (`event_audiencetype_id`)
    REFERENCES `events`.`audiencetype` (`audiencetype_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `events`.`presenter`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`presenter` ;

CREATE TABLE IF NOT EXISTS `events`.`presenter` (
  `presenter_id` INT NOT NULL,
  `presenter_event_id` INT NOT NULL,
  `presenter_firstname` VARCHAR(25) NOT NULL,
  `presenter_lastname` VARCHAR(25) NOT NULL,
  `presenter_company` VARCHAR(255) NOT NULL,
  `presenter_shortbio` TEXT NULL,
  PRIMARY KEY (`presenter_id`),
  UNIQUE INDEX `lecturer_id_UNIQUE` (`presenter_id` ASC),
  INDEX `presenter_event_idx` (`presenter_event_id` ASC),
  CONSTRAINT `presenter_event`
    FOREIGN KEY (`presenter_event_id`)
    REFERENCES `events`.`event` (`event_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `events`.`attendeetype`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`attendeetype` ;

CREATE TABLE IF NOT EXISTS `events`.`attendeetype` (
  `attendeetype_id` INT NOT NULL,
  `attendeetype_name` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`attendeetype_id`));


-- -----------------------------------------------------
-- Table `events`.`attendee`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`attendee` ;

CREATE TABLE IF NOT EXISTS `events`.`attendee` (
  `attendee_id` INT NOT NULL,
  `attendee_firstname` VARCHAR(255) NOT NULL,
  `attendee_lastname` VARCHAR(255) NOT NULL,
  `attendee_type` INT NOT NULL,
  `attendee_student_year` INT NULL,
  PRIMARY KEY (`attendee_id`),
  INDEX `attendee_attendeetype_idx` (`attendee_type` ASC),
  CONSTRAINT `attendee_attendeetype`
    FOREIGN KEY (`attendee_type`)
    REFERENCES `events`.`attendeetype` (`attendeetype_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Table `events`.`signup`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `events`.`signup` ;

CREATE TABLE IF NOT EXISTS `events`.`signup` (
  `signup_event_id` INT NOT NULL,
  `signup_attendee_id` INT NOT NULL,
  PRIMARY KEY (`signup_event_id`, `signup_attendee_id`),
  INDEX `signup_attendee_idx` (`signup_attendee_id` ASC),
  CONSTRAINT `signup_event`
    FOREIGN KEY (`signup_event_id`)
    REFERENCES `events`.`event` (`event_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `signup_attendee`
    FOREIGN KEY (`signup_attendee_id`)
    REFERENCES `events`.`attendee` (`attendee_id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION);


-- -----------------------------------------------------
-- Data for table `events`.`time`
-- -----------------------------------------------------
START TRANSACTION;
USE `events`;
INSERT INTO `events`.`time` (`time_id`, `time`) VALUES (1, '8:00');
INSERT INTO `events`.`time` (`time_id`, `time`) VALUES (2, '8:30');
INSERT INTO `events`.`time` (`time_id`, `time`) VALUES (4, '9:00');

COMMIT;


-- -----------------------------------------------------
-- Data for table `events`.`campus`
-- -----------------------------------------------------
START TRANSACTION;
USE `events`;
INSERT INTO `events`.`campus` (`campus_id`, `campus_name`, `campus_shortname`) VALUES (1, 'Zagreb', 'ZG');
INSERT INTO `events`.`campus` (`campus_id`, `campus_name`, `campus_shortname`) VALUES (2, 'Dubrovnik', 'DU');
INSERT INTO `events`.`campus` (`campus_id`, `campus_name`, `campus_shortname`) VALUES (3, 'Rochester', 'RO');

COMMIT;


-- -----------------------------------------------------
-- Data for table `events`.`room`
-- -----------------------------------------------------
START TRANSACTION;
USE `events`;
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (1, 2, 'Room 1', NULL, NULL);
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (2, 2, 'Room 3', NULL, NULL);
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (3, 2, 'Room 11', NULL, NULL);
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (4, 2, 'Room 15', NULL, true);
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (5, 2, 'Room 21', NULL, NULL);
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (6, 2, 'Room 31', NULL, NULL);
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (7, 2, 'Lab 35', 20, true);
INSERT INTO `events`.`room` (`room_id`, `campus_id`, `room_name`, `room_capacity`, `room_videolink`) VALUES (8, 1, 'Lab 3', 50, true);

COMMIT;


-- -----------------------------------------------------
-- Data for table `events`.`day`
-- -----------------------------------------------------
START TRANSACTION;
USE `events`;
INSERT INTO `events`.`day` (`day_id`, `day_name`) VALUES (1, 'Monday');
INSERT INTO `events`.`day` (`day_id`, `day_name`) VALUES (2, 'Tuesday');
INSERT INTO `events`.`day` (`day_id`, `day_name`) VALUES (3, 'Wednesday');
INSERT INTO `events`.`day` (`day_id`, `day_name`) VALUES (4, 'Thursday');
INSERT INTO `events`.`day` (`day_id`, `day_name`) VALUES (5, 'Friday');
INSERT INTO `events`.`day` (`day_id`, `day_name`) VALUES (6, 'Saturday');

COMMIT;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
