-- Write raw SQL queries here.

--
-- insert page route
--

INSERT INTO `topology` (`topology_name`, `topology_parent`, `topology_page`, `topology_order`, `topology_group`, `topology_url`, `topology_url_opt`, `is_react`)
VALUES ('Trap Menu Entry', '1', '112', '11', '1', '/home/trap', NULL, '1');


--
-- create tables
--

CREATE TABLE IF NOT EXISTS `mod_traps_objects` (
   `id` int(11) NOT NULL AUTO_INCREMENT,
   `name` varchar(254) DEFAULT NULL,
   `description` varchar(1024) DEFAULT NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

INSERT INTO `mod_traps_objects` (`name`, `description`) VALUES
('trap1', 'description of trap 1'),
('trap2', 'description of trap 2'),
('trap3', 'description of trap 3'),
('trap4', 'description of trap 4'),
('trap5', 'description of trap 5'),
('trap6', 'description of trap 6'),
('trap7', 'description of trap 7'),
('trap8', 'description of trap 8'),
('trap9', 'description of trap 9'),
('trap10', 'description of trap 10');
