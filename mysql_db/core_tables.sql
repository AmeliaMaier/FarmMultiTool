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
ALTER TABLE `core_sources` CHANGE `address_isbn` `address_isbn` VARCHAR (250) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL;
ALTER TABLE `core_sources`
    ADD UNIQUE (`address_isbn`);

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
ALTER TABLE `core_animal_species` CHANGE `daily_feed_amount` `daily_feed_amount` FLOAT(11) NULL DEFAULT NULL;
ALTER TABLE `core_animal_species` ADD `daily_feed_amount` INT NULL DEFAULT NULL AFTER `eats_plants`, ADD `feed_amount_unit` TEXT NULL DEFAULT NULL AFTER `daily_feed_amount`, ADD `daily_feed_per_unit` TEXT NULL DEFAULT NULL AFTER `feed_amount_unit`;
ALTER TABLE `core_animal_species`
    ADD CONSTRAINT `core_animal_species_ibfk_1` FOREIGN KEY (`core_source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_animal_species`
    ADD UNIQUE `core_animal_species_unique_index`(`core_source_id`, `species_name`);
ALTER TABLE `core_animal_species`
    ADD CONSTRAINT `core_animal_species_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `core_animal_species`
    ADD `created_dt` DATE NOT NULL AFTER `gestation_days`, ADD `deleted_dt` DATE NULL DEFAULT NULL AFTER `created_dt`;

CREATE TABLE `farmmult_core`.`core_animal_breed`
(
    `id`             BIGINT       NOT NULL AUTO_INCREMENT,
    `user_id`        BIGINT       NOT NULL,
    `core_source_id` BIGINT       NOT NULL,
    `species_id`     BIGINT       NOT NULL,
    `breed_name`     VARCHAR(250) NOT NULL,
    `min_size`       INT NULL DEFAULT NULL,
    `max_size`       INT NULL DEFAULT NULL,
    `size_units`     TEXT NULL DEFAULT NULL,
    `meat_source`    BOOLEAN NULL DEFAULT NULL,
    `milk_source`    BOOLEAN NULL DEFAULT NULL,
    `egg_source`     BOOLEAN NULL DEFAULT NULL,
    `fiber_source`   BOOLEAN NULL DEFAULT NULL,
    `summer_happy`   BOOLEAN NULL DEFAULT NULL,
    `winter_happy`   BOOLEAN NULL DEFAULT NULL,
    `endangered`     BOOLEAN NULL DEFAULT NULL,
    `exotic`         BOOLEAN NULL DEFAULT NULL,
    `color`          TEXT NULL DEFAULT NULL,
    `price_child`    DOUBLE NULL DEFAULT NULL,
    `price_adult`    DOUBLE NULL DEFAULT NULL,
    `created_dt`     DATE         NOT NULL,
    `deleted_dt`     DATE NULL DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY              `fk_core_animal_breed_source_id` (`core_source_id`),
    KEY              `fk_core_animal_breed_user_id` (`user_id`),
    KEY              `fk_core_animal_breed_species_id` (`species_id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_animal_breed`
    ADD CONSTRAINT `core_animal_breed_ibfk_1`
        FOREIGN KEY (`core_source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_animal_breed`
    ADD CONSTRAINT `core_animal_breed_ibfk_2`
        FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `core_animal_breed`
    ADD CONSTRAINT `core_animal_breed_ibfk_3`
        FOREIGN KEY (`species_id`) REFERENCES `core_animal_species` (`id`);
ALTER TABLE `core_animal_breed`
    ADD UNIQUE `core_animal_breed_unique_index`
    (`core_source_id`, `species_id`, `breed_name`);
ALTER TABLE `core_animal_breed`
    ADD `difficulty_level` TEXT NOT NULL AFTER `price_adult`;

CREATE TABLE `farmmult_core`.`core_source_archive`
(
    `id`             BIGINT NOT NULL AUTO_INCREMENT,
    `user_id`        BIGINT NOT NULL,
    `source_id`      BIGINT NOT NULL,
    `sftp_folder_id` BIGINT NOT NULL,
    `sftp_file_id`   BIGINT NOT NULL,
    `share_url`      TEXT NULL DEFAULT NULL,
    `created_dt`     DATE   NOT NULL,
    `deleted_dt`     DATE NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_source_archive` CHANGE `sftp_file_id` `sftp_file_name` TEXT NOT NULL;
ALTER TABLE `core_source_archive`
    ADD CONSTRAINT `core_source_archive_ibfk_1`
        FOREIGN KEY (`source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_source_archive`
    ADD CONSTRAINT `core_source_archive_ibfk_2`
        FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `core_source_archive`
    ADD CONSTRAINT `core_source_archive_ibfk_3`
        FOREIGN KEY (`sftp_folder_id`) REFERENCES `sftp_folders` (`sftp_folder_id`);


CREATE TABLE `farmmult_core`.`core_plant_species`
(
    `id`                 BIGINT       NOT NULL AUTO_INCREMENT,
    `user_id`            BIGINT       NOT NULL,
    `species_name`       VARCHAR(250) NOT NULL,
    `sun_level`          TEXT NULL DEFAULT NULL,
    `growth_zone`        TEXT NULL DEFAULT NULL,
    `water_requirements` TEXT NULL DEFAULT NULL,
    `height`             INT NULL DEFAULT NULL,
    `height_unit`        TEXT NULL DEFAULT NULL,
    `soil_type`          TEXT NULL DEFAULT NULL,
    `human_edible`       BOOLEAN NULL DEFAULT NULL,
    `days_to_harvest`    INT NULL DEFAULT NULL,
    `perennial`          BOOLEAN NULL DEFAULT NULL,
    `created_dt`         DATE         NOT NULL,
    `deleted_dt`         DATE NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_plant_species`
    ADD `core_source_id` BIGINT NOT NULL AFTER `user_id`;
ALTER TABLE `core_plant_species`
    ADD CONSTRAINT `core_plant_species_ibfk_1` FOREIGN KEY (`core_source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_plant_species`
    ADD UNIQUE `core_plant_species_unique_index`(`core_source_id`, `species_name`);
ALTER TABLE `core_plant_species`
    ADD CONSTRAINT `core_plant_species_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);

CREATE TABLE `farmmult_core`.`core_animal_food_plants`
(
    `id`                BIGINT NOT NULL AUTO_INCREMENT,
    `animal_species_id` BIGINT NOT NULL,
    `plant_species_id`  BIGINT NOT NULL,
    `medical_use`       BOOLEAN NULL DEFAULT NULL,
    `limit_access`      BOOLEAN NULL DEFAULT NULL,
    `free_feed`         BOOLEAN NULL DEFAULT NULL,
    `teething`          BOOLEAN NULL DEFAULT NULL,
    `grit`              BOOLEAN NULL DEFAULT NULL,
    `user_id`           BIGINT NOT NULL,
    `core_source_id`    BIGINT NOT NULL,
    `notes`             TEXT NULL DEFAULT NULL,
    `created_dt`        DATE   NOT NULL,
    `deleted_dt`        DATE   NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_animal_food_plants`
    ADD CONSTRAINT `core_animal_food_plants_ibfk_1` FOREIGN KEY (`core_source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_animal_food_plants`
    ADD UNIQUE `core_animal_food_plants_unique_index`(`core_source_id`, `animal_species_id`, `plant_species_id`);
ALTER TABLE `core_animal_food_plants`
    ADD CONSTRAINT `core_animal_food_plants_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `core_animal_food_plants`
    ADD CONSTRAINT `core_animal_food_plants_ibfk_3` FOREIGN KEY (`animal_species_id`) REFERENCES `core_animal_species` (`id`);
ALTER TABLE `core_animal_food_plants`
    ADD CONSTRAINT `core_animal_food_plants_ibfk_4` FOREIGN KEY (`plant_species_id`) REFERENCES `core_plant_species` (`id`);

CREATE TABLE `farmmult_core`.`core_animal_events`
(
    `id`                BOOLEAN      NOT NULL AUTO_INCREMENT,
    `user_id`           BIGINT       NOT NULL,
    `core_source_id`    BIGINT       NOT NULL,
    `animal_species_id` BIGINT       NOT NULL,
    `animal_breed_id`   BIGINT NULL DEFAULT NULL,
    `event_name`        VARCHAR(250) NOT NULL,
    `created_dt`        DATE         NOT NULL,
    `deleted_dt`        DATE NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_animal_events` CHANGE `id` `id` BIGINT(1) NOT NULL AUTO_INCREMENT;
ALTER TABLE `core_animal_events`
    ADD CONSTRAINT `core_animal_events_ibfk_1` FOREIGN KEY (`core_source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_animal_events`
    ADD UNIQUE `core_animal_events_unique_index`(`core_source_id`, `animal_species_id`, `event_name`, `animal_breed_id`);
ALTER TABLE `core_animal_events`
    ADD CONSTRAINT `core_animal_events_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `core_animal_events`
    ADD CONSTRAINT `core_animal_events_ibfk_3` FOREIGN KEY (`animal_species_id`) REFERENCES `core_animal_species` (`id`);

CREATE TABLE `farmmult_core`.`core_animal_event_links`
(
    `id`               BIGINT NOT NULL AUTO_INCREMENT,
    `core_source_id`   BIGINT NOT NULL,
    `user_id`          BIGINT NOT NULL,
    `current_event_id` BIGINT NOT NULL,
    `next_event_id`    BIGINT NOT NULL,
    `time_between`     BIGINT NOT NULL,
    `time_unit`        INT    NOT NULL,
    `created_dt`       DATE   NOT NULL,
    `deleted_dt`       DATE NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `core_animal_event_links` CHANGE `time_unit` `time_unit` TEXT NOT NULL;
ALTER TABLE `core_animal_event_links`
    ADD CONSTRAINT `core_animal_event_links_ibfk_1` FOREIGN KEY (`core_source_id`) REFERENCES `core_sources` (`id`);
ALTER TABLE `core_animal_event_links`
    ADD UNIQUE `core_animal_event_links_unique_index`(`core_source_id`, `current_event_id`, `next_event_id`);
ALTER TABLE `core_animal_event_links`
    ADD CONSTRAINT `core_animal_event_links_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `core_animal_event_links`
    ADD CONSTRAINT `core_animal_event_links_ibfk_3` FOREIGN KEY (`current_event_id`) REFERENCES `core_animal_events` (`id`);
ALTER TABLE `core_animal_event_links`
    ADD CONSTRAINT `core_animal_event_links_ibfk_4` FOREIGN KEY (`next_event_id`) REFERENCES `core_animal_events` (`id`);

