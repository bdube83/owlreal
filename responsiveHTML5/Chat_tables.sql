CREATE TABLE `users` (
  `iduser` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(45) NOT NULL,
  PRIMARY KEY (`iduser`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;


CREATE TABLE `chat` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted_on` datetime NOT NULL,
  `message` text NOT NULL,
  `topic` char(50) NOT NULL,
  `title` char(50) NOT NULL,
  `uplift` int(11) NOT NULL,
  `subtopic_id` int(15) NOT NULL,
  `iduser` int(11) NOT NULL,
  `picpath` varchar(255) NOT NULL DEFAULT '"blank"',
  PRIMARY KEY (`message_id`),
  KEY `iduser_fk_idx` (`iduser`),
  CONSTRAINT `iduser_fk` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=latin1;

CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted_on` datetime NOT NULL,
  `iduser` int(11) NOT NULL,
  `comment` varchar(45) NOT NULL,
  `message_id` int(11) NOT NULL,
  `uplift` int(11) NOT NULL,
  `picpath` varchar(255) NOT NULL,
  UNIQUE KEY `comment_id` (`comment_id`),
  KEY `comment_ibfk_1_idx` (`message_id`),
  KEY `user_id_fk_idx` (`iduser`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `chat` (`message_id`),
  CONSTRAINT `user_id_fk` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;

CREATE TABLE `profile_pic` (
  `idprofile_pic` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `iduser` int(11) NOT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`idprofile_pic`),
  KEY `comment_fk1_idx` (`comment_id`),
  KEY `message_fk1_idx` (`message_id`),
  KEY `iduser_fk1_idx` (`iduser`),
  CONSTRAINT `comment_fk1` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `iduser_fk1` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `message_fk1` FOREIGN KEY (`message_id`) REFERENCES `chat` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `comment_report_fk_idx` (`comment_id`),
  KEY `message_report_fk_idx` (`message_id`),
  KEY `user_report_fk_idx` (`iduser`),
  CONSTRAINT `comment_report_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `message_report_fk` FOREIGN KEY (`message_id`) REFERENCES `chat` (`message_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_report_fk` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=latin1;

CREATE TABLE `uplift` (
  `uplift_id` int(11) NOT NULL AUTO_INCREMENT,
  `comment_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`uplift_id`),
  KEY `comment_fk_idx` (`comment_id`),
  KEY `message_fk_idx` (`message_id`),
  KEY `user_fk_idx` (`iduser`),
  CONSTRAINT `comment_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `message_fk` FOREIGN KEY (`message_id`) REFERENCES `chat` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `user_fk` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=75 DEFAULT CHARSET=latin1;

