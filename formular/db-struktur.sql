CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,


  `username` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(255) NOT NULL DEFAULT '',
  `password` varchar(255) NOT NULL DEFAULT '',
  `comment` text NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 3,
  PRIMARY KEY (`id`),
  KEY `email` (`email`),
  KEY `username` (`username`),
  KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

CREATE TABLE `groups` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `name` varchar(255) NOT NULL DEFAULT '',
    `comment` text NOT NULL,
    PRIMARY KEY (`id`),
    KEY `name` (`name`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;

CREATE TABLE groups_users (
    `user_id` int unsigned NOT NULL DEFAULT '0',
    `group_id` int unsigned NOT NULL DEFAULT '0',
    PRIMARY KEY (`group_id`, `user_id`),
    KEY `user_id` (`user_id`),
    KEY `group_id` (`group_id`)
);

CREATE TABLE `roles` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) NOT NULL UNIQUE,
    `created` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `updated` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb3;

ALTER TABLE `users` DROP FOREIGN KEY `fk_role_id`;

ALTER TABLE `users` DROP COLUMN `role_id`;

ALTER TABLE `users`
ADD COLUMN `role_id` int unsigned NOT NULL DEFAULT 3;

ALTER TABLE `users`
MODIFY COLUMN `role_id` int unsigned NOT NULL DEFAULT 3;

ALTER TABLE `users`
ADD COLUMN `pwd_reset_pending` bool NOT NULL DEFAULT FALSE;

-- Insert predefined roles into the roles table
INSERT INTO `roles` (`name`) VALUES ('admin'), ('editor'), ('user');

INSERT INTO `groups_users` (`user_id`, `group_id`) VALUES ('1', '1');

INSERT INTO `groups_users` (`user_id`, `group_id`) VALUES ('2', '1');

INSERT INTO `groups_users` (`user_id`, `group_id`) VALUES ('1', '2');

INSERT INTO `groups_users` (`user_id`, `group_id`) VALUES ('2', '2');

select *
from users
    left join groups_users on groups_users.user_id = users.id
    left join groups on groups.id = groups_users.group_id
where
    users.id = 1;

select *
from groups
    left join groups_users on groups_users.group_id = id
    left join users on groups_users.user_id = users.id
where
    groups.id > 0;

-- ######################################################################################
-- Hardcoded users and groups in case of new creation
-- ######################################################################################

-- INSERT INTO
--     `users` (
--         `id`,
--         `created`,
--         `updated`,
--         `username`,
--         `email`,
--         `password`,
--         `comment`
--     )
-- VALUES (
--         NULL,
--         '2024-07-23 11:04:00',
--         '2024-07-23 11:04:00',
--         'max',
--         'max@x.de',
--         'secure',
--         'blabla'
--     );

-- INSERT INTO
--     `users` (
--         `id`,
--         `created`,
--         `updated`,
--         `username`,
--         `email`,
--         `password`,
--         `comment`
--     )
-- VALUES (
--         NULL,
--         '2024-07-23 11:04:00',
--         '2024-07-23 11:04:00',
--         'max2',
--         'max2@x.de',
--         'secure2',
--         'blabla'
--     );

-- INSERT INTO
--     `groups` (
--         `id`,
--         `created`,
--         `updated`,
--         `name`,
--         `comment`
--     )
-- VALUES (
--         NULL,
--         '2024-07-23 11:04:00',
--         '2024-07-23 11:04:00',
--         'admin',
--         'asdfasdf'
--     );

-- INSERT INTO
--     `groups` (
--         `id`,
--         `created`,
--         `updated`,
--         `name`,
--         `comment`
--     )
-- VALUES (
--         NULL,
--         '2024-07-23 11:04:00',
--         '2024-07-23 11:04:00',
--         'kunden',
--         'asdfasdf'
--     );