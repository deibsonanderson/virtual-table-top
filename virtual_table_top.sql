-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 16-Jul-2026 às 16:06
-- Versão do servidor: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `virtual_table_top`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_vtt_note`
--

CREATE TABLE `tb_vtt_note` (
  `code` int(11) NOT NULL,
  `title` varchar(500) DEFAULT NULL,
  `note` text,
  `created_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_vtt_session`
--

CREATE TABLE `tb_vtt_session` (
  `code` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `map` varchar(255) NOT NULL,
  `zoom` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `tb_vtt_token`
--

CREATE TABLE `tb_vtt_token` (
  `code` varchar(255) NOT NULL,
  `session` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `top_position` int(11) DEFAULT NULL,
  `left_position` int(11) DEFAULT NULL,
  `width_position` int(11) DEFAULT NULL,
  `height_position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_vtt_note`
--
ALTER TABLE `tb_vtt_note`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `tb_vtt_session`
--
ALTER TABLE `tb_vtt_session`
  ADD PRIMARY KEY (`code`);

--
-- Indexes for table `tb_vtt_token`
--
ALTER TABLE `tb_vtt_token`
  ADD PRIMARY KEY (`code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_vtt_note`
--
ALTER TABLE `tb_vtt_note`
  MODIFY `code` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
