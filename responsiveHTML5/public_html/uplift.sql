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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=latin1;
