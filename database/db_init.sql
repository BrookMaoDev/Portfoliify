SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

CREATE DATABASE IF NOT EXISTS `res_profile`;

USE `res_profile`;

CREATE TABLE `users` (
    `user_id` INT NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(128) DEFAULT NULL,
    `email` VARCHAR(128) DEFAULT NULL,
    `password` VARCHAR(128) DEFAULT NULL,
    PRIMARY KEY (`user_id`)
) ENGINE = InnoDB;

CREATE TABLE `institution` (
    `institution_id` INT NOT NULL AUTO_INCREMENT,
    `NAME` VARCHAR(128) DEFAULT NULL,
    PRIMARY KEY (`institution_id`),
    UNIQUE (`NAME`)
) ENGINE = InnoDB;

CREATE TABLE `profile` (
    `profile_id` INT NOT NULL AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `first_name` TEXT DEFAULT NULL,
    `last_name` TEXT DEFAULT NULL,
    `email` TEXT DEFAULT NULL,
    `headline` TEXT DEFAULT NULL,
    `summary` TEXT DEFAULT NULL,
    PRIMARY KEY (`profile_id`),
    FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `position` (
    `position_id` INT NOT NULL AUTO_INCREMENT,
    `profile_id` INT NOT NULL,
    `rank` INT DEFAULT NULL,
    `year` INT DEFAULT NULL,
    `description` TEXT DEFAULT NULL,
    PRIMARY KEY (`position_id`),
    FOREIGN KEY (`profile_id`) REFERENCES `profile` (`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

CREATE TABLE `education` (
    `profile_id` INT NOT NULL,
    `institution_id` INT NOT NULL,
    `rank` INT DEFAULT NULL,
    `YEAR` INT DEFAULT NULL,
    FOREIGN KEY (`profile_id`) REFERENCES `profile` (`profile_id`) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (`institution_id`) REFERENCES `institution` (`institution_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB;

INSERT INTO
    `institution` (`NAME`)
VALUES ('University of Toronto'),
    (
        'University of British Columbia'
    ),
    ('McGill University'),
    ('University of Alberta'),
    ('University of Waterloo'),
    ('Western University'),
    ('Queen\'s University'),
    ('University of Calgary'),
    ('McMaster University'),
    ('University of Ottawa');

INSERT INTO
    `users` (`name`, `email`, `password`)
VALUES (
        'John Doe',
        'johndoe@example.com',
        '62828864736551c894b87fb137cf6959' -- The salted hash value for "password123"
    );

COMMIT;