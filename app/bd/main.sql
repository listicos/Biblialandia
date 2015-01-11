SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';


-- -----------------------------------------------------
-- Table `biblia_trivia`.`language`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`language` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `code` VARCHAR(5) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(100) NULL,
  `name` VARCHAR(45) NULL,
  `lastname` VARCHAR(45) NULL,
  `facebook_id` MEDIUMTEXT NULL,
  `facebook_token` VARCHAR(300) NULL,
  `google_id` MEDIUMTEXT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `language_id` INT NOT NULL,
  `last_session` TIMESTAMP NULL,
  `status` VARCHAR(45) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_user_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `biblia_trivia`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`question_category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`question_category` (
  `id` INT NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question` VARCHAR(250) NULL,
  `user_id` INT NOT NULL,
  `question_category_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `language_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_question_user1_idx` (`user_id` ASC),
  INDEX `fk_question_question_category1_idx` (`question_category_id` ASC),
  INDEX `fk_question_language1_idx` (`language_id` ASC),
  CONSTRAINT `fk_question_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `biblia_trivia`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_question_question_category1`
    FOREIGN KEY (`question_category_id`)
    REFERENCES `biblia_trivia`.`question_category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_question_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `biblia_trivia`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`answer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_id` INT NOT NULL,
  `answer` VARCHAR(150) NULL,
  `correct` TINYINT(1) NULL,
  `status` VARCHAR(25) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_answer_question_idx` (`question_id` ASC),
  CONSTRAINT `fk_answer_question`
    FOREIGN KEY (`question_id`)
    REFERENCES `biblia_trivia`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`user_answer`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`user_answer` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `answer_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_has_answer_answer1_idx` (`answer_id` ASC),
  INDEX `fk_user_has_answer_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_has_answer_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `biblia_trivia`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_answer_answer1`
    FOREIGN KEY (`answer_id`)
    REFERENCES `biblia_trivia`.`answer` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`friendship`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`friendship` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `friend_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL,
  `type` VARCHAR(45) NULL,
  INDEX `fk_user_has_user_user2_idx` (`friend_id` ASC),
  INDEX `fk_user_has_user_user1_idx` (`user_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_user_has_user_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `biblia_trivia`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_user_user2`
    FOREIGN KEY (`friend_id`)
    REFERENCES `biblia_trivia`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`trophy`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`trophy` (
  `id` INT NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`user_trophy`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`user_trophy` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `trophy_id` INT NOT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_has_trophy_trophy1_idx` (`trophy_id` ASC),
  INDEX `fk_user_has_trophy_user1_idx` (`user_id` ASC),
  CONSTRAINT `fk_user_has_trophy_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `biblia_trivia`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_trophy_trophy1`
    FOREIGN KEY (`trophy_id`)
    REFERENCES `biblia_trivia`.`trophy` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`question_error`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`question_error` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`qualification`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`qualification` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`user_rate_question`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`user_rate_question` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `user_id` INT NOT NULL,
  `question_id` INT NOT NULL,
  `qualification_id` INT NOT NULL,
  `question_error_id` INT NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_user_has_question_question1_idx` (`question_id` ASC),
  INDEX `fk_user_has_question_user1_idx` (`user_id` ASC),
  INDEX `fk_user_rate_question_question_error1_idx` (`question_error_id` ASC),
  INDEX `fk_user_rate_question_qualification1_idx` (`qualification_id` ASC),
  CONSTRAINT `fk_user_has_question_user1`
    FOREIGN KEY (`user_id`)
    REFERENCES `biblia_trivia`.`user` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_has_question_question1`
    FOREIGN KEY (`question_id`)
    REFERENCES `biblia_trivia`.`question` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_rate_question_question_error1`
    FOREIGN KEY (`question_error_id`)
    REFERENCES `biblia_trivia`.`question_error` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_user_rate_question_qualification1`
    FOREIGN KEY (`qualification_id`)
    REFERENCES `biblia_trivia`.`qualification` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`question_category_description`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`question_category_description` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `question_category_id` INT NOT NULL,
  `language_id` INT NOT NULL,
  `name` VARCHAR(60) NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_question_category_has_language_language1_idx` (`language_id` ASC),
  INDEX `fk_question_category_has_language_question_category1_idx` (`question_category_id` ASC),
  CONSTRAINT `fk_question_category_has_language_question_category1`
    FOREIGN KEY (`question_category_id`)
    REFERENCES `biblia_trivia`.`question_category` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_question_category_has_language_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `biblia_trivia`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `biblia_trivia`.`trophy_description`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `biblia_trivia`.`trophy_description` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `trophy_id` INT NOT NULL,
  `language_id` INT NOT NULL,
  `name` VARCHAR(45) NULL,
  `description` VARCHAR(45) NULL,
  INDEX `fk_trophy_has_language_language1_idx` (`language_id` ASC),
  INDEX `fk_trophy_has_language_trophy1_idx` (`trophy_id` ASC),
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_trophy_has_language_trophy1`
    FOREIGN KEY (`trophy_id`)
    REFERENCES `biblia_trivia`.`trophy` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_trophy_has_language_language1`
    FOREIGN KEY (`language_id`)
    REFERENCES `biblia_trivia`.`language` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
