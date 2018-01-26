-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2017 at 07:53 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `name_and_synonym`
--

-- --------------------------------------------------------

--
-- Table structure for table `logical_chars`
--

CREATE TABLE IF NOT EXISTS `logical_chars` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(10) COLLATE utf8_general_mysql500_ci NOT NULL,
  `logical_char` char(2) COLLATE utf8_general_mysql500_ci NOT NULL,
  `position` int(5) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `puzzles`
--

CREATE TABLE IF NOT EXISTS `puzzles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(25) COLLATE utf8_unicode_ci NOT NULL,
  `word_list` varchar(500) COLLATE utf8_unicode_ci NOT NULL,
  `created_by` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `created_by` (`created_by`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `puzzles`
--

INSERT INTO `puzzles` (`id`, `word`, `word_list`, `created_by`) VALUES
(1, 'metro', 'mumble,mutter,take,remove,bad,atrocious,old,archiac,begin,commence', 'jmal713@gmail.com'),
(2, 'meet', 'mumble,mutter,take,remove,take,remove,bad,atrocious', 'jmal713@gmail.com'),
(3, 'test', 'adasd,asdasd,asdasd,asdasd,asdasdasd,asdasd', ''),
(4, 'testing', 'akjdhkasjdh,ajdaskdjsdhakjdhaskjdh,akjdhakjsdhaksdhasd,akjdhaskjdhkashdkjah,asdhakjsdhaskdhaskjdh,aksjdhakjsdhaskdhakshdas,asdahsdakshdaksd,asdhjasdkhsa', '');

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

CREATE TABLE IF NOT EXISTS `registered_users` (
  `user_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `activation_token` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL,
  PRIMARY KEY (`user_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`user_email`, `display_name`, `password`, `is_verified`, `activation_token`, `role`) VALUES
('a.shakir2010@gmail.com', 'shakir.a', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1),
('abdi.abdul25@gmail.com', 'abdi.a', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1),
('bekzmail@gmail.com', 'mike.b', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1),
('isaiah.a.schultz@gmail.com', 'isaiah.s', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1),
('jmal713@gmail.com', 'jamal.m', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `synonyms`
--

CREATE TABLE IF NOT EXISTS `synonyms` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(25) COLLATE utf8_general_mysql500_ci NOT NULL,
  `rep_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_general_mysql500_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `synonyms`
--

INSERT INTO `synonyms` (`id`, `word`, `rep_id`) VALUES
(1, '6545,metro', 5),
(2, 'asdasdsa,adasdasd', 1),
(3, 'as,dasdasd', 1),
(4, 'asdasdsdsadsad,dsadsadsad', 1),
(5, 'sadsadsd,asdsad', 1),
(6, 'asdas,das', 1),
(7, 'das,dasdasd', 1),
(8, 'asdasd,asd', 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
