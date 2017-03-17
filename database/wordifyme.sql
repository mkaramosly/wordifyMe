-- phpMyAdmin SQL Dump
-- version 4.5.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Mar 17, 2017 at 01:40 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wordifyme`
--

-- --------------------------------------------------------

--
-- Table structure for table `Files`
--

CREATE TABLE `Files` (
  `fileId` int(10) NOT NULL,
  `path` varchar(50) NOT NULL,
  `filename` varchar(50) NOT NULL,
  `uploadedAt` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Meeting`
--

CREATE TABLE `Meeting` (
  `meetingId` int(10) NOT NULL,
  `projectId` int(10) NOT NULL,
  `fileId` int(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `startTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endTimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `transcriptId` int(10) NOT NULL,
  `transcribed` enum('0','1','2') NOT NULL DEFAULT '0',
  `parsed` enum('0','1','2') NOT NULL DEFAULT '0',
  `analyzed` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Meeting_Words_Pid`
--

CREATE TABLE `Meeting_Words_Pid` (
  `meetingWordId` int(10) NOT NULL,
  `meetingId` int(10) NOT NULL,
  `word` varchar(100) NOT NULL,
  `startTimestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `endTimestamp` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `confidence` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Transcript`
--

CREATE TABLE `Transcript` (
  `transcriptId` int(10) NOT NULL,
  `transcript` text NOT NULL,
  `confidence` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `word_alternative_pid`
--

CREATE TABLE `word_alternative_pid` (
  `wordAlternativeId` int(10) NOT NULL,
  `meetingWordId` int(10) NOT NULL,
  `confidence` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Files`
--
ALTER TABLE `Files`
  ADD PRIMARY KEY (`fileId`);

--
-- Indexes for table `Meeting`
--
ALTER TABLE `Meeting`
  ADD PRIMARY KEY (`meetingId`);

--
-- Indexes for table `Meeting_Words_Pid`
--
ALTER TABLE `Meeting_Words_Pid`
  ADD PRIMARY KEY (`meetingWordId`);

--
-- Indexes for table `Transcript`
--
ALTER TABLE `Transcript`
  ADD PRIMARY KEY (`transcriptId`);

--
-- Indexes for table `word_alternative_pid`
--
ALTER TABLE `word_alternative_pid`
  ADD PRIMARY KEY (`wordAlternativeId`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
