CREATE TABLE `farmmult_core`.`sftp_folders`
(
    `user_id`    BIGINT NOT NULL,
    `name`       TEXT   NOT NULL,
    `sftp_folder_id`    BIGINT NOT NULL,
    `created_dt` DATE   NOT NULL,
    `deleted_dt` DATE   NULL DEFAULT NULL
) ENGINE = InnoDB DEFAULT CHARSET=latin1;
ALTER TABLE `sftp_folders`
    ADD CONSTRAINT `sftp_folders_ibfk_1`
        FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
ALTER TABLE `sftp_folders`
    ADD UNIQUE `sftp_folders_unique_index`
        (`sftp_id`);