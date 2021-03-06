-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2017 at 01:29 PM
-- Server version: 10.1.24-MariaDB
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `voting`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id_no` varchar(255) NOT NULL,
  `password` char(60) NOT NULL,
  `user_level` varchar(13) NOT NULL,
  `is_active` int(1) NOT NULL,
  `is_voted` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id_no`, `password`, `user_level`, `is_active`, `is_voted`) VALUES
('14000', '$2gCXDSrN2DHo', 'Student', 0, 0),
('14124', '$23fjJ7riUMLg', 'Student', 1, 0),
('2323233432', '$2V3qOC70ekLE', 'Student', 1, 0),
('42', '*0', 'Administrator', 1, 0),
('43', '$252R4mH4yfRU', 'Student', 0, 0),
('432244', '$252R4mH4yfRU', 'Student', 0, 0),
('4324', '$252R4mH4yfRU', 'Student', 0, 0),
('43242', '$2V3qOC70ekLE', 'Student', 0, 0),
('adf', '$252R4mH4yfRU', 'Student', 0, 0),
('admin', '$2gCXDSrN2DHo', 'Administrator', 0, 0),
('adsf', '$2V3qOC70ekLE', 'Student', 0, 0),
('adsfff', '$252R4mH4yfRU', 'Student', 0, 0),
('afdsafdsfdsa', '$252R4mH4yfRU', 'Student', 0, 0),
('dafdf', '$2gCXDSrN2DHo', 'Student', 0, 0),
('fddfdfasfdas', '$252R4mH4yfRU', 'Student', 0, 0),
('ffdfsddfsdfsdf', '$252R4mH4yfRU', 'Student', 0, 0),
('fsddsffsd', '$252R4mH4yfRU', 'Student', 0, 0),
('java', '$23fjJ7riUMLg', 'Student', 0, 0),
('jer', '$23fjJ7riUMLg', 'Student', 0, 0),
('sdaf', '$252R4mH4yfRU', 'Student', 0, 0),
('sdfaafsddfasafds', '$252R4mH4yfRU', 'Student', 0, 0),
('voter', '$2ejLZltgUIFI', 'Student', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `candidates`
--

CREATE TABLE `candidates` (
  `pk` int(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `votes` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `candidates`
--

INSERT INTO `candidates` (`pk`, `photo`, `fname`, `mname`, `lname`, `category`, `votes`) VALUES
(1, 'default.png', 'Dahlia', 'Jaskolski', 'Little', 'President', 0),
(2, 'default.png', 'Trace ', 'Rippin ', 'Harber', 'Secretary', 0),
(3, 'default.png', 'Dangelo ', 'Wolff ', 'Waters', 'Treasurer', 0),
(4, 'default.png', 'Elody ', 'Bosco ', 'Block', 'Treasurer', 0),
(5, 'default.png', 'Karolann ', 'Koepp ', 'Hermann', 'Vice president', 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `name` varchar(255) NOT NULL,
  `number_of_candidates` int(255) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`name`, `number_of_candidates`, `date_created`) VALUES
('President', 1, '2017-03-19 18:10:16'),
('Secretary', 1, '2017-03-19 18:12:31'),
('Treasurer', 1, '2017-03-19 18:13:33'),
('Vice president', 1, '2017-03-19 18:11:35');

-- --------------------------------------------------------

--
-- Table structure for table `profiles`
--

CREATE TABLE `profiles` (
  `id_no` varchar(255) NOT NULL,
  `fname` varchar(255) NOT NULL,
  `mname` varchar(255) NOT NULL,
  `lname` varchar(255) NOT NULL,
  `gender` char(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `profiles`
--

INSERT INTO `profiles` (`id_no`, `fname`, `mname`, `lname`, `gender`) VALUES
('14000', 'Dahlia ', 'Jaskolski ', 'Little', 'F'),
('14124', 'Trace ', 'Rippin ', 'Harber', 'F'),
('2323233432', 'Dangelo ', 'Wolff ', 'Waters', 'F'),
('42', 'Jane', 'Will', 'Doe', 'F'),
('43', 'Elody ', 'Bosco ', 'Block', 'F'),
('432244', 'Karolann ', 'Koepp ', 'Hermann', 'F'),
('4324', 'fdsadafskj', 'lkjkj', 'ljklkjk', 'F'),
('43242', 'sdffdskl', 'kjkjlkj', 'kj', 'F'),
('adf', 'dfsasfd', 'jklk', 'kjkjl', 'F'),
('admin', 'John', 'Will', 'Doe', 'M'),
('adsf', 'dfaadfsj', 'klkj', 'lkjlkj', 'F'),
('adsfff', 'ddfasafsd', 'adfs', 'adfs', 'F'),
('afdsafdsfdsa', 'fdsafdsjkkj', 'ljlk', 'lkjlkjlk', 'F'),
('dafdf', 'fdsfds', 'daadfdfsjk', 'kjkjll', 'F'),
('fddfdfasfdas', 'dfsadsfafdsl;', 'kjkjl', 'lkjlkj', 'F'),
('ffdfsddfsdfsdf', 'dfsa', 'jojlk', 'ljj', 'F'),
('fsddsffsd', 'dfaadfsj', 'klkj', 'lkjlkj', 'F'),
('java', 'dasfds', 'sdfdfsa', 'adsfdfsdfs', 'M'),
('jer', 'fdjsdafkjlkl', 'jkjkljkl', 'j', 'F'),
('sdaf', 'fsdfdsafsdnm', 'kjkl', 'kjjkl', 'F'),
('sdfaafsddfasafds', 'dfaadfsj', 'klkj', 'lkjlkj', 'F'),
('voter', 'I', 'am', 'Voter', 'M');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id_no` varchar(255) NOT NULL,
  `grade_level` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id_no`, `grade_level`, `section`) VALUES
('14000', 'Grade 7', 'Mapayapa'),
('14124', 'Grade 7', 'Mapayapa'),
('2323233432', 'Grade 10', 'Magiting'),
('43', 'Grade 8', 'Mapayapa'),
('432244', 'Grade 8', 'Maagap'),
('4324', 'fdas', 'adf'),
('43242', '2', '12'),
('adf', 'sdfa', 'afds'),
('adsf', 'dfa', 'adfs'),
('adsfff', 'adsf', 'fdsa'),
('afdsafdsfdsa', 'sdfa', 'dsfa'),
('dafdf', 'asdf', 'daadsfdfsa'),
('fddfdfasfdas', 'adfs', 'adfs'),
('ffdfsddfsdfsdf', 'f', 'f'),
('fsddsffsd', 'Grade 8', 'Mapayapa'),
('java', 'gradeh', 'sec'),
('jer', 'dfadl', 'kjkkj'),
('sdaf', 'daffd', 'dasf'),
('sdfaafsddfasafds', 'dfa', 'adfs'),
('voter', 'Grade 7', 'Masinop');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id_no`),
  ADD KEY `accounts_user_level_fk` (`user_level`);

--
-- Indexes for table `candidates`
--
ALTER TABLE `candidates`
  ADD PRIMARY KEY (`pk`),
  ADD KEY `candidates_category_fk` (`category`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`name`);

--
-- Indexes for table `profiles`
--
ALTER TABLE `profiles`
  ADD PRIMARY KEY (`id_no`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id_no`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `candidates`
--
ALTER TABLE `candidates`
  MODIFY `pk` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_id_no_fk` FOREIGN KEY (`id_no`) REFERENCES `profiles` (`id_no`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `candidates`
--
ALTER TABLE `candidates`
  ADD CONSTRAINT `candidates_category_fk` FOREIGN KEY (`category`) REFERENCES `categories` (`name`) ON UPDATE CASCADE;

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_id_no_fk` FOREIGN KEY (`id_no`) REFERENCES `profiles` (`id_no`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
