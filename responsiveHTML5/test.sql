-- phpMyAdmin SQL Dump
-- version 4.0.4.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jan 15, 2015 at 02:55 AM
-- Server version: 5.5.32
-- PHP Version: 5.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `chat`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `posted_on` datetime NOT NULL,
  `user_name` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `topic` char(15) NOT NULL,
  `title` char(15) NOT NULL,
  `uplift` int(11) NOT NULL,
  `subtopic_id` int(15) NOT NULL,
  `comment_id` int(11) NOT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`message_id`, `posted_on`, `user_name`, `message`, `topic`, `title`, `uplift`, `subtopic_id`, `comment_id`) VALUES
(1, '2015-01-15 00:38:54', 'John', 'In the beginnig...', 'General', 'Beginning', 0, 0, 0),
(6, '2015-01-15 03:44:22', 'John', 'God Loves You', 'Love', 'john3:16', 0, 0, 0),
(8, '2015-01-15 03:49:23', 'John', 'My grace is enough...', 'Grace', '2Cor12:9', 0, 0, 0);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
