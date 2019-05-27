CREATE TABLE `report` (
  `idreport` int(11) NOT NULL,
  `comment_id` int(11) DEFAULT NULL,
  `message_id` int(11) DEFAULT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`idreport`),
  KEY `comment_report_fk_idx` (`comment_id`),
  KEY `message_report_fk_idx` (`message_id`),
  KEY `user_report_fk_idx` (`iduser`),
  CONSTRAINT `comment_report_fk` FOREIGN KEY (`comment_id`) REFERENCES `comment` (`comment_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `message_report_fk` FOREIGN KEY (`message_id`) REFERENCES `chat` (`message_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `user_report_fk` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
