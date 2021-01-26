-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jan 25, 2021 at 07:53 PM
-- Server version: 5.7.33-log
-- PHP Version: 7.3.6



--
-- Table structure for table `user_logins`
--

CREATE TABLE IF NOT EXISTS `user_logins` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `user_name` text NOT NULL,
    `user_password` text NOT NULL,
    `user_salt` text NOT NULL,
    `created_dt` date NOT NULL,
    `deleted_dt` date DEFAULT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `user_type`
--

CREATE TABLE IF NOT EXISTS `user_type` (
    `id` bigint(20) NOT NULL AUTO_INCREMENT,
    `user_id` bigint(20) NOT NULL,
    `type` text NOT NULL,
    `created_dt` date NOT NULL,
    `deleted_dt` date DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `fk_user_type_user_logins` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `user_type`
--
ALTER TABLE `user_type`
    ADD CONSTRAINT `user_type_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user_logins` (`id`);
COMMIT;