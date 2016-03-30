CREATE TABLE `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted_on` datetime NOT NULL,
  `iduser` int(11) NOT NULL,
  `comment` varchar(45) NOT NULL,
  `message_id` int(11) NOT NULL,
  `uplift` int(11) NOT NULL,
  UNIQUE KEY `comment_id` (`comment_id`),
  KEY `comment_ibfk_1_idx` (`message_id`),
  KEY `user_id_fk_idx` (`iduser`),
  CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `chat` (`message_id`),
  CONSTRAINT `user_id_fk` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=latin1;
