# Create Testuser
CREATE USER 'teo'@'localhost' IDENTIFIED BY 'teo';
GRANT SELECT,INSERT,UPDATE,DELETE,CREATE,DROP ON *.* TO 'teo'@'localhost';
# Create DB
CREATE DATABASE IF NOT EXISTS `teo` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `teo`;
# Create Table
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` int(11) NOT NULL,
  `created_on` timestamp NULL DEFAULT NULL,
  `modified_on` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `username` varchar(50) DEFAULT NULL,
  `salt` varchar(20) DEFAULT NULL,
  `password` varchar(50) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `firstname` varchar(50) DEFAULT NULL,
  `lastname` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT;
# Add Data