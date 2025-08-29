-- phpMyAdmin SQL Dump
-- version 4.4.12
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2025 at 06:26 AM
-- Server version: 5.6.25
-- PHP Version: 5.6.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sdh_inject`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`) VALUES
(6, 'Chairman', 'chairman', 'ch*SDP12'),
(7, 'Saman', 'samanra', 'sam*SDP'),
(8, 'Erandaka', 'jerandaka', 'jera*SDP'),
(9, 'Harsha', 'harshak', 'harshak'),
(10, 'Tharanga', 'tharangaa', 'thara*SDP'),
(11, 'Ravindra', 'ravindrap', 'SDP*rav'),
(12, 'Dinukdim', 'dinukdim', 'SDP*dinu'),
(13, 'Gayan_OLD', 'gayan_OLD', 'Gaya*SDP_OLD'),
(14, 'Sanjeevani', 'sanjeevani', 'Sanj*SDP'),
(15, 'Indika Jayadasa', 'Indika', 'Indk*Jyds'),
(16, 'Udaya', 'udayae', 'Udy*SDP'),
(17, 'Abeeth', 'abeeth', 'Abt*SDP'),
(18, 'Sheersha', 'sheersha', 'Shrsh*SDP'),
(19, 'Nandana', 'nandana', 'Nan*12sd'),
(20, 'Muditha', 'muditha', 'muditha*12sd'),
(21, 'gayanc', 'gayanc', 'gayanc*12sd'),
(22, 'dewwandir', 'dewwandir', 'dewwandir*12sd'),
(23, 'ranjith', 'ranjith', 'ranjith'),
(24, 'Irushi Lankani', 'irsudhil', 'irsudhil'),
(25, 'Sasha Munasinghe', 'Sasha', 'Munasinghe'),
(26, 'Adarshee Hettiarachchi', 'Adarshee', 'Hettiarachchi'),
(27, 'Ms. Chamari', 'Chamari', 'Chmr'),
(28, 'Mr. ANURA RATNAYAKE', 'Anura', 'AnuraR'),
(29, 'Oshini', 'Oshini', 'Oshini'),
(30, 'Lakmal', 'LakmalJ', 'LakmalJ'),
(31, 'Malshani', 'Malshani', 'Malshani*fdo'),
(32, 'Gayan', 'gayan', 'gayan@sd'),
(33, 'Sahan', 'sahan', 'sahan@sd'),
(34, 'Self', 'self', 'self@sd'),
(35, 'Mr. Amal Raj', 'AmalR', 'AmalR'),
(36, 'Vidurangi', 'Vidurangi', 'VidurangiN'),
(37, 'Pasindu', 'Pasindu', 'PasinduG'),
(38, 'Padmal', 'PadmalL', 'PadmalL'),
(39, 'Gayumee', 'GayumeeT', 'GayumeeT'),
(40, 'Nawoda', 'NawodaD', 'NawodaD'),
(41, 'Samadhee', 'SamadheeK', 'SamadheeK');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=42;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
