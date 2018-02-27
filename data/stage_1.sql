CREATE TABLE IF NOT EXISTS `service` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(100) NOT NULL,
    `priority` tinyint UNSIGNED NOT NULL,
    `slug` varchar(100) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `comment` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `author` varchar(50) NOT NULL,
    `message` TEXT NOT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `published_at` DATE DEFAULT NULL,
    `visibility` tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `page` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `visibility` tinyint(1) NOT NULL DEFAULT 1,
    `slug` varchar(50) NOT NULL,
    `title` varchar(100) NOT NULL,
    `content` TEXT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `service_content` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `service_id` int(11) NOT NULL,
    `content` TEXT NOT NULL,
    `content_json` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `service_content_field` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `service_id` int(11) NOT NULL,
    `field_type` enum('text', 'date', 'select') NOT NULL,
    `field_id` varchar(20) NOT NULL,
    `field_label` varchar(100) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `service_content_option` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `service_id` int(11) NOT NULL,
    `field_id` int(11) NOT NULL,
    `option_value` varchar(50) NOT NULL,
    `option_id` varchar(25) NOT NULL,
    `option_label` varchar(50) NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
    FOREIGN KEY (`field_id`) REFERENCES `service_content_field` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `service_content_price` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `service_id` int(11) NOT NULL,
    `formula` TEXT NOT NULL,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`service_id`) REFERENCES `service` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
