-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2017 at 01:35 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.5.38

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `name_and_synonym`
--

-- --------------------------------------------------------

--
-- Table structure for table `registered_users`
--

CREATE TABLE `registered_users` (
  `user_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `display_name` varchar(15) COLLATE utf8_unicode_ci NOT NULL,
  `password` char(64) COLLATE utf8_unicode_ci NOT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `activation_token` char(100) COLLATE utf8_unicode_ci NOT NULL,
  `role` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `registered_users`
--

INSERT INTO `registered_users` (`user_email`, `display_name`, `password`, `is_verified`, `activation_token`, `role`) VALUES
('a.shakir2010@gmail.com', 'shakir.a', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1),
('abdi.abdul25@gmail.com', 'abdi.a', '3mSs.Uqh6QoYo', 1, '', 1),
('admin', 'admin', 'QGfjlqtIMEazY', 1, '', 1),
('bekzmail@gmail.com', 'mike.b', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1),
('isaiah.a.schultz@gmail.com', 'isaiah.s', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1),
('jmal713@gmail.com', 'jamal.m', '63b013549306167e953641cb3a64a9d008afeee5', 1, '', 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `registered_users`
--
ALTER TABLE `registered_users`
  ADD PRIMARY KEY (`user_email`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
