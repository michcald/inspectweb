-- phpMyAdmin SQL Dump
-- version 4.0.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 05, 2013 at 04:35 PM
-- Server version: 5.6.14
-- PHP Version: 5.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `inspectweb`
--
CREATE DATABASE IF NOT EXISTS `inspectweb` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `inspectweb`;

-- --------------------------------------------------------

--
-- Table structure for table `challenges`
--

CREATE TABLE IF NOT EXISTS `challenges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `start` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `end` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `theme` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenges_steps`
--

CREATE TABLE IF NOT EXISTS `challenges_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idchallenge` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=427 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenges_steps_questions`
--

CREATE TABLE IF NOT EXISTS `challenges_steps_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idstep` int(11) NOT NULL,
  `question` text NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1718 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenges_steps_questions_answers`
--

CREATE TABLE IF NOT EXISTS `challenges_steps_questions_answers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idquestion` int(11) NOT NULL,
  `idteam` int(11) NOT NULL,
  `answer` text NOT NULL,
  `score` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3435 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenges_teams`
--

CREATE TABLE IF NOT EXISTS `challenges_teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idchallenge` int(11) NOT NULL,
  `name` varchar(200) NOT NULL,
  `bonus` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

-- --------------------------------------------------------

--
-- Table structure for table `challenges_teams_members`
--

CREATE TABLE IF NOT EXISTS `challenges_teams_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idteam` int(11) NOT NULL,
  `idopensim` varchar(100) NOT NULL,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=158 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=29 ;

-- --------------------------------------------------------

--
-- Table structure for table `teams_members`
--

CREATE TABLE IF NOT EXISTS `teams_members` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idteam` int(11) NOT NULL,
  `idauth` varchar(100) NOT NULL,
  `role` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=95 ;

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE IF NOT EXISTS `themes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `themes_libraries`
--

CREATE TABLE IF NOT EXISTS `themes_libraries` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

-- --------------------------------------------------------

--
-- Table structure for table `themes_libraries_questions`
--

CREATE TABLE IF NOT EXISTS `themes_libraries_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idlibrary` int(11) NOT NULL,
  `question` text NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

-- --------------------------------------------------------

--
-- Table structure for table `themes_steps`
--

CREATE TABLE IF NOT EXISTS `themes_steps` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idtheme` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=60 ;

-- --------------------------------------------------------

--
-- Table structure for table `themes_steps_questions`
--

CREATE TABLE IF NOT EXISTS `themes_steps_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idstep` int(11) NOT NULL,
  `question` text NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=239 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
