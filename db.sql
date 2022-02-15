
USE leaderboard;

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(256) NOT NULL,
  `age` int(3) NOT NULL,
  `points` int(11) DEFAULT 0,
  `street` text NOT NULL,
  `city` text NOT NULL,
  `state` text NOT NULL,
  `country` text NOT NULL,
  `zip` text NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `user` (`id`, `name`, `age`, `street`, `city`, `state`, `country`, `zip`, `date_created`) VALUES
(1, 'Emma', 25, '11 Test Dr', 'Miami', 'Floria', 'USA', '12345', now()),
(2, 'Noah', 33, '55 Rank Blvd', 'Toronto', 'Ontario', 'Canada', 'M1P9U1', now()),
(3, 'James', 28, '123 Mason Cres', 'Houston', 'Texas', 'USA', '32123', now()),
(4, 'William', 41, '8 Red Dr', 'Boston', 'Massachusetts', 'USA', '51233', now()),
(5, 'Olivia', 23, '19 Canuck Ave', 'Vancouver', 'British Columbia', 'Canada', 'A2P1V9', now());

