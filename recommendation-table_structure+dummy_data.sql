-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2018 at 07:01 AM
-- Server version: 10.1.26-MariaDB
-- PHP Version: 7.1.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recommendation`
--

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

CREATE TABLE `chat` (
  `chat_id` int(11) NOT NULL,
  `teacher_u_id` int(11) NOT NULL,
  `parent_u_id` int(11) NOT NULL,
  `sender` int(11) NOT NULL,
  `message` text NOT NULL,
  `time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `chat`
--

INSERT INTO `chat` (`chat_id`, `teacher_u_id`, `parent_u_id`, `sender`, `message`, `time`) VALUES
(1, 1, 3, 1, 'Selamat datang, parent1', '2018-05-12 11:49:50'),
(2, 1, 3, 1, 'Silahkan bertanya apabila ada yang ditanyakan', '2018-05-12 11:50:06'),
(3, 1, 4, 1, 'Selamat datang, parent2', '2018-05-12 11:50:15'),
(4, 1, 4, 1, 'Silahkan bertanya apabila ada yang ditanyakan', '2018-05-12 11:50:21'),
(5, 2, 5, 2, 'Selamat datang, parent3', '2018-05-12 11:50:51'),
(6, 2, 5, 2, 'Silahkan bertanya apabila ada yang ditanyakan', '2018-05-12 11:50:53'),
(7, 2, 6, 2, 'Selamat datang, parent4', '2018-05-12 11:51:01'),
(8, 2, 6, 2, 'Silahkan bertanya apabila ada yang ditanyakan', '2018-05-12 11:51:03');

-- --------------------------------------------------------

--
-- Table structure for table `meeting`
--

CREATE TABLE `meeting` (
  `meeting_id` int(11) NOT NULL,
  `meeting_request_id` int(11) NOT NULL,
  `location` text NOT NULL,
  `time` text NOT NULL,
  `note` text NOT NULL,
  `status` enum('waiting','complete','canceled') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `meeting_request`
--

CREATE TABLE `meeting_request` (
  `request_id` int(11) NOT NULL,
  `parent_u_id` int(11) NOT NULL,
  `teacher_u_id` int(11) NOT NULL,
  `subject` text NOT NULL,
  `message` text NOT NULL,
  `date` date NOT NULL,
  `status` enum('waiting','accept','reject') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `meeting_request`
--

INSERT INTO `meeting_request` (`request_id`, `parent_u_id`, `teacher_u_id`, `subject`, `message`, `date`, `status`) VALUES
(1, 3, 1, 'Mengenai Bidang Studi Anak', 'Ada beberapa pertanyaan yang ingin saya tanyakan mengenai anak saya', '2018-05-24', 'waiting');

-- --------------------------------------------------------

--
-- Table structure for table `relation`
--

CREATE TABLE `relation` (
  `relation_id` int(11) NOT NULL,
  `teacher_u_id` int(11) NOT NULL,
  `parent_u_id` int(11) NOT NULL,
  `student_u_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `relation`
--

INSERT INTO `relation` (`relation_id`, `teacher_u_id`, `parent_u_id`, `student_u_id`) VALUES
(1, 1, 3, 0),
(2, 1, 4, 0),
(3, 2, 5, 0),
(4, 2, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `u_id` int(11) NOT NULL,
  `username` varchar(16) NOT NULL,
  `password` varchar(32) NOT NULL,
  `profile` enum('student','parent','teacher','headmaster') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`u_id`, `username`, `password`, `profile`) VALUES
(1, 'teacher1', 'password', 'teacher'),
(2, 'teacher2', 'password', 'teacher'),
(3, 'parent1', 'password', 'parent'),
(4, 'parent2', 'password', 'parent'),
(5, 'parent3', 'password', 'parent'),
(6, 'parent4', 'password', 'parent');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `meeting`
--
ALTER TABLE `meeting`
  ADD PRIMARY KEY (`meeting_id`),
  ADD UNIQUE KEY `meeting_request_id` (`meeting_request_id`);

--
-- Indexes for table `meeting_request`
--
ALTER TABLE `meeting_request`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `relation`
--
ALTER TABLE `relation`
  ADD PRIMARY KEY (`relation_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`u_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
--
-- AUTO_INCREMENT for table `meeting`
--
ALTER TABLE `meeting`
  MODIFY `meeting_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `meeting_request`
--
ALTER TABLE `meeting_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `relation`
--
ALTER TABLE `relation`
  MODIFY `relation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `u_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
