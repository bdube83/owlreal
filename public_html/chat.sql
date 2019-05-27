CREATE TABLE `chat` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted_on` datetime NOT NULL,
  `message` text NOT NULL,
  `topic` char(15) NOT NULL,
  `title` char(15) NOT NULL,
  `uplift` int(11) NOT NULL,
  `subtopic_id` int(15) NOT NULL,
  `iduser` int(11) NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `iduser_fk_idx` (`iduser`),
  CONSTRAINT `iduser_fk` FOREIGN KEY (`iduser`) REFERENCES `users` (`iduser`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;
