CREATE TABLE `farmmult_core`.`core_sources`
(
    `id`           BIGINT NOT NULL AUTO_INCREMENT,
    `user_id`      BIGINT NOT NULL,
    `source_type`  TEXT   NOT NULL,
    `address_isbn` TEXT   NOT NULL,
    `title`        TEXT   NOT NULL,
    `created_dt`   DATE   NOT NULL,
    `deleted_dt`   DATE DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY            `fk_core_sources_user_logins` (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_sources`
    ADD CONSTRAINT `user_type_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);

CREATE TABLE `farmmult_core`.`core_animal_species`
(
    `id`               BIGINT NOT NULL AUTO_INCREMENT,
    `core_source_id`   BIGINT NOT NULL,
    `species_name`     TEXT   NOT NULL,
    `meat_source`      BOOLEAN NULL DEFAULT NULL,
    `fiber_source`     BOOLEAN NULL DEFAULT NULL,
    `milk_source`      BOOLEAN NULL DEFAULT NULL,
    `egg_source`       BOOLEAN NULL DEFAULT NULL,
    `cage_happy`       BOOLEAN NULL DEFAULT NULL,
    `pasture_happy`    BOOLEAN NULL DEFAULT NULL,
    `difficulty_level` TEXT NULL DEFAULT NULL,
    `eats_bugs`        BOOLEAN NULL DEFAULT NULL,
    `eats_meat`        BOOLEAN NULL DEFAULT NULL,
    `eats_plants`      BOOLEAN NULL DEFAULT NULL,
    `min_temp`         INT NULL DEFAULT NULL,
    `max_temp`         INT NULL DEFAULT NULL,
    `vaccine_schedule` BOOLEAN NULL DEFAULT NULL,
    `gestation_days`   INT NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY                `fk_core_animal_species_source_id` (`core_source_id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_animal_species` CHANGE `species_name` `species_name` VARCHAR (250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `core_animal_species`
    ADD `user_id` BIGINT NOT NULL AFTER `id`;
ALTER TABLE `core_animal_species`
    ADD CONSTRAINT `core_animal_species_ibfk_1` FOREIGN KEY (`core_source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_animal_species`
    ADD UNIQUE `core_animal_species_unique_index`(`core_source_id`, `species_name`);
ALTER TABLE `core_animal_species`
    ADD CONSTRAINT `core_animal_species_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `core_animal_species`
    ADD `created_dt` DATE NOT NULL AFTER `gestation_days`, ADD `deleted_dt` DATE NULL DEFAULT NULL AFTER `created_dt`;