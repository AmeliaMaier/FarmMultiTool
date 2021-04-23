CREATE TABLE `farmmult_core`.`farm_plant_sources`
(
    `id`              BIGINT       NOT NULL AUTO_INCREMENT,
    `source_name`     VARCHAR(255) NOT NULL,
    `source_address`  VARCHAR(255) NOT NULL,
    `provides_seeds`  BOOLEAN      NULL DEFAULT NULL,
    `provides_plants` BOOLEAN      NULL DEFAULT NULL,
    `user_id`         BIGINT       NOT NULL,
    `created_dt`      DATE         NOT NULL,
    `deleted_dt`      DATE         NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
ALTER TABLE `farm_plant_sources`
    ADD CONSTRAINT `farm_plant_sources_user_ibfk` FOREIGN KEY (`user_id`)
        REFERENCES `user_logins` (`id`);
ALTER TABLE `farm_plant_sources`
    ADD UNIQUE `farm_plant_sources_unique_index`(`source_name`, `source_address`);

CREATE TABLE `farmmult_core`.`farm_plant_locations`
(
    `id`              BIGINT       NOT NULL AUTO_INCREMENT,
    `location_name`   VARCHAR(255) NOT NULL,
    `location_lat`    FLOAT        NULL DEFAULT NULL,
    `location_long`   FLOAT        NULL DEFAULT NULL,
    `user_id`         BIGINT       NOT NULL,
    `created_dt`      DATE         NOT NULL,
    `deleted_dt`      DATE         NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB;
ALTER TABLE `farm_plant_locations`
    ADD CONSTRAINT `farm_plant_locations_user_ibfk` FOREIGN KEY (`user_id`)
        REFERENCES `user_logins` (`id`);
ALTER TABLE `farm_plant_locations`
    ADD UNIQUE `farm_plant_locations_unique_index`(`location_name`);


CREATE TABLE `farmmult_core`.`farm_plants`
(
    `id`                      BIGINT NOT NULL AUTO_INCREMENT,
    `core_plant_species_id`   BIGINT NOT NULL,
    `plant_source_id`         BIGINT NOT NULL,
    `plant_starting_age_days` INT    NOT NULL,
    `plant_location_id`       BIGINT NOT NULL,
    `user_id`                 BIGINT NOT NULL,
    `created_dt`              DATE   NOT NULL,
    `deleted_dt`              DATE   NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB
  DEFAULT CHARSET = latin1;
ALTER TABLE `farm_plants`
    ADD CONSTRAINT `farm_plants_user_ibfk` FOREIGN KEY (`user_id`)
        REFERENCES `user_logins` (`id`);
ALTER TABLE `farm_plants`
    ADD CONSTRAINT `farm_plants_plant_source_ibfk` FOREIGN KEY (`plant_source_id`)
        REFERENCES `farm_plant_sources` (`id`);
ALTER TABLE `farm_plants`
    ADD CONSTRAINT `farm_plants_location_ibfk` FOREIGN KEY (`plant_location_id`)
        REFERENCES `farm_plant_locations` (`id`);