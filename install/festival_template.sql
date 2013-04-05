-- phpMyAdmin SQL Dump
-- version 3.4.9
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 22, 2013 at 04:29 PM
-- Server version: 5.5.29
-- PHP Version: 5.3.10-1ubuntu3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `festival_template`
--

-- --------------------------------------------------------

--
-- Table structure for table `bands`
--

CREATE TABLE IF NOT EXISTS `bands` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `master_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `day` int(11) NOT NULL,
  `stage` int(11) NOT NULL,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `sec_start` int(11) NOT NULL,
  `sec_end` int(11) NOT NULL,
  `genre` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=109 ;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `band` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `comment` varchar(1024) NOT NULL,
  `discussed` varchar(4096) NOT NULL,
  `discuss_current` varchar(4096) NOT NULL,
  `pinned` varchar(4096) NOT NULL,
  `ignored` varchar(4096) NOT NULL,
  `group` varchar(8192) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `comms`
--

CREATE TABLE IF NOT EXISTS `comms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromuser` int(11) NOT NULL,
  `band` int(11) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `displayed` int(11) NOT NULL,
  `commstring` varchar(1024) NOT NULL,
  `commtype` int(11) NOT NULL,
  `location` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=26 ;

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE IF NOT EXISTS `days` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(25) NOT NULL,
  `date` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `info`
--

CREATE TABLE IF NOT EXISTS `info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item` varchar(255) NOT NULL,
  `value` varchar(1024) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Table structure for table `links`
--

CREATE TABLE IF NOT EXISTS `links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `band` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `link` varchar(256) NOT NULL,
  `clicks` int(11) NOT NULL,
  `descrip` varchar(25) NOT NULL DEFAULT 'Check it out',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `live_rating`
--

CREATE TABLE IF NOT EXISTS `live_rating` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` int(11) NOT NULL,
  `band` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `comment` varchar(8192) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE IF NOT EXISTS `locations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `pics`
--

CREATE TABLE IF NOT EXISTS `pics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `band` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `pic` blob NOT NULL,
  `clicks` int(11) NOT NULL,
  `descrip` varchar(25) NOT NULL DEFAULT 'Check it out',
  `name` varchar(100) NOT NULL,
  `type` varchar(100) NOT NULL,
  `size` float NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `band` int(11) NOT NULL,
  `user` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `recommendations`
--

CREATE TABLE IF NOT EXISTS `recommendations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fromuser` int(11) NOT NULL,
  `touser` int(11) NOT NULL,
  `band` int(11) NOT NULL,
  `followed` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stages`
--

CREATE TABLE IF NOT EXISTS `stages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
