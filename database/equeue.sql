-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2025 at 06:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `equeue`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_audit_log`
--

CREATE TABLE `admin_audit_log` (
  `id` int(11) NOT NULL,
  `staff_id` int(11) NOT NULL,
  `action` varchar(255) NOT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_audit_log`
--

INSERT INTO `admin_audit_log` (`id`, `staff_id`, `action`, `details`, `ip_address`, `user_agent`, `created_at`) VALUES
(319, 48, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-05 16:04:59'),
(320, 48, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-05 16:05:41'),
(321, 49, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-05 16:05:46'),
(322, 49, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 07:50:08'),
(323, 49, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 08:42:22'),
(324, 48, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 08:42:29'),
(325, 48, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 14:22:30'),
(326, 49, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 14:22:38'),
(327, 49, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 14:22:51'),
(328, 48, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 14:22:59'),
(329, 48, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 14:30:24'),
(330, 49, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 14:30:34'),
(331, 49, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 15:30:03'),
(332, 49, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 15:30:09'),
(333, 49, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 15:30:13'),
(334, 48, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 15:30:21'),
(335, 48, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 15:30:27'),
(336, 48, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 16:32:17'),
(337, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 16:32:24'),
(338, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 16:32:34'),
(339, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 16:32:54'),
(340, 48, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:24:06'),
(341, 48, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:24:35'),
(342, 49, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:24:40'),
(343, 49, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:25:12'),
(344, 48, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:25:19'),
(345, 48, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:25:35'),
(346, 49, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:25:40'),
(347, 49, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:40:35'),
(348, 48, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36', '2025-11-08 17:40:42');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`) VALUES
(1, 'General Medicine'),
(2, 'Pediatrics'),
(3, 'Dental'),
(4, 'Obstetrics and Gynecology (OB-GYN)'),
(5, 'Ophthalmology'),
(6, 'Dermatology');

-- --------------------------------------------------------

--
-- Table structure for table `department_staff`
--

CREATE TABLE `department_staff` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `role` varchar(50) NOT NULL DEFAULT 'Medical Assistant'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `department_staff`
--

INSERT INTO `department_staff` (`id`, `name`, `department_id`, `role`) VALUES
(1, 'Dr. John Smith', 1, 'Doctors'),
(2, 'Dr. Evelyn Reed', 1, 'Doctors'),
(3, 'Dr. Michael Chen', 2, 'Doctors'),
(4, 'Dr. Sarah Jones', 2, 'Doctors'),
(5, 'Dr. David Garcia', 3, 'Doctors'),
(6, 'Dr. Maria Angela Cruz', 4, 'Doctors'),
(7, 'Dr. Kristine Dela Peña', 4, 'Doctors'),
(8, 'Dr. Liza Mendoza', 4, 'Doctors'),
(9, 'Dr. Raymond Santos', 5, 'Doctors'),
(10, 'Dr. Clarisse Go', 5, 'Doctors'),
(11, 'Dr. Benedict Uy', 5, 'Doctors'),
(12, 'Dr. Trisha Villanueva', 6, 'Doctors'),
(13, 'Dr. Paolo Lim', 6, 'Doctors'),
(14, 'Dr. Hannah Robles', 6, 'Doctors'),
(16, 'Alice Reyes (Medical Assistant)', 1, 'Medical Assistant'),
(17, 'John Cruz (Medical Assistant)', 1, 'Medical Assistant'),
(18, 'Maria Santos (Medical Assistant)', 2, 'Medical Assistant'),
(19, 'Kevin Lim (Medical Assistant)', 2, 'Medical Assistant'),
(20, 'Hannah Dela Vega (Medical Assistant)', 3, 'Medical Assistant'),
(21, 'Mark Fernandez (Medical Assistant)', 3, 'Medical Assistant'),
(22, 'Clara Villanueva (Medical Assistant)', 4, 'Medical Assistant'),
(23, 'Paul Uy (Medical Assistant)', 4, 'Medical Assistant'),
(24, 'Grace Mendoza (Medical Assistant)', 5, 'Medical Assistant'),
(25, 'Daniel Lopez (Medical Assistant)', 5, 'Medical Assistant'),
(26, 'Emily Robles (Medical Assistant)', 6, 'Medical Assistant'),
(27, 'Joseph Tan (Medical Assistant)', 6, 'Medical Assistant');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) NOT NULL,
  `age` int(11) NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `reason_for_visit` text DEFAULT NULL,
  `parent_guardian` varchar(255) DEFAULT NULL,
  `queue_number` int(11) NOT NULL,
  `department_id` int(11) NOT NULL,
  `department_staff_id` int(11) DEFAULT NULL,
  `status` enum('waiting','called','in consultation','done','cancelled','no show') NOT NULL DEFAULT 'waiting',
  `check_in_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `birthdate` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` enum('male','female','prefer not to say') DEFAULT NULL,
  `civil_status` enum('single','married','widow') DEFAULT NULL,
  `registration_datetime` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `first_name`, `middle_name`, `last_name`, `age`, `contact_number`, `reason_for_visit`, `parent_guardian`, `queue_number`, `department_id`, `department_staff_id`, `status`, `check_in_time`, `created_at`, `birthdate`, `address`, `gender`, `civil_status`, `registration_datetime`) VALUES
(215, 'Jaz', 'Dacumos', 'Villanueva', 22, '09567990016', 'Check-up', 'N/A', 1, 1, 1, 'cancelled', '2025-11-05 16:05:32', '2025-11-05 16:05:32', '2003-06-11', 'aaaaaaaaa', 'male', 'single', '2025-11-06 00:05:00'),
(216, 'John', 'Doe', '', 35, '1234567890', 'Checkup', 'Self', 2, 1, 1, 'done', '2025-11-08 08:40:25', '2025-11-08 08:40:25', '1990-01-01', NULL, NULL, NULL, NULL),
(217, 'Jane', 'Smith', '', 40, '0987654321', 'Follow-up', 'Self', 3, 1, 1, 'done', '2025-11-08 08:41:07', '2025-11-08 08:41:07', '1985-05-15', NULL, NULL, NULL, NULL),
(218, 'John', '', 'Doe', 23, '09567990016', 'Follow-up', 'N/A', 4, 1, 1, 'done', '2025-11-08 14:22:26', '2025-11-08 14:22:26', '2002-06-11', 'Sumacab', 'male', 'single', '2025-11-08 22:22:00'),
(219, 'John', '', 'Smith', 0, '09567990016', 'Consultation', 'Mon Tumbaga', 5, 1, 1, 'cancelled', '2025-11-08 14:23:23', '2025-11-08 14:23:23', '2025-11-06', 'asasasa', 'male', 'single', '2025-11-08 22:23:00'),
(220, 'Test1', '', 'Patient', 35, '1234567890', 'Test', 'Self', 6, 1, 1, 'cancelled', '2025-11-08 14:29:27', '2025-11-08 14:29:27', '1990-01-01', NULL, NULL, NULL, NULL),
(221, 'Test2', '', 'Patient', 35, '1234567891', 'Test', 'Self', 7, 1, 1, 'cancelled', '2025-11-08 14:29:50', '2025-11-08 14:29:50', '1990-01-01', NULL, NULL, NULL, NULL),
(222, 'FinalTest', '', 'Patient', 35, '1234567892', 'Test', 'Self', 8, 1, 1, 'done', '2025-11-08 14:31:10', '2025-11-08 14:31:10', '1990-01-01', NULL, NULL, NULL, NULL),
(223, 'John', '', 'Doe', 21, '09567990016', 'Check-up', 'N/A', 1, 1, 1, 'waiting', '2025-11-08 17:24:32', '2025-11-08 17:24:32', '2004-05-05', 'asas', 'male', 'single', '2025-11-09 01:24:00');

-- --------------------------------------------------------

--
-- Table structure for table `patient_vitals`
--

CREATE TABLE `patient_vitals` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `bp` varchar(50) DEFAULT NULL,
  `temp` varchar(50) DEFAULT NULL,
  `cr_pr` varchar(50) DEFAULT NULL,
  `rr` varchar(50) DEFAULT NULL,
  `wt` varchar(50) DEFAULT NULL,
  `o2sat` varchar(50) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patient_vitals`
--

INSERT INTO `patient_vitals` (`id`, `patient_id`, `bp`, `temp`, `cr_pr`, `rr`, `wt`, `o2sat`, `recorded_at`) VALUES
(1, 215, '120/80', '36.5°C', '80', '16', '55kg', '89%', '2025-11-05 16:05:32');

-- --------------------------------------------------------

--
-- Table structure for table `queue_history`
--

CREATE TABLE `queue_history` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `old_status` varchar(50) DEFAULT NULL,
  `new_status` varchar(50) DEFAULT NULL,
  `department_id` int(11) NOT NULL,
  `department_staff_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue_history`
--

INSERT INTO `queue_history` (`id`, `patient_id`, `action`, `old_status`, `new_status`, `department_id`, `department_staff_id`, `staff_id`, `created_at`) VALUES
(467, 215, 'registered', NULL, 'waiting', 1, 1, 48, '2025-11-05 16:05:32'),
(468, 216, 'status_changed', 'waiting', 'in consultation', 1, 1, 49, '2025-11-08 08:42:18'),
(469, 216, 'status_changed', 'in consultation', 'done', 1, 1, 49, '2025-11-08 08:42:19'),
(470, 217, 'status_changed', 'waiting', 'in consultation', 1, 1, 49, '2025-11-08 08:42:20'),
(471, 217, 'status_changed', 'in consultation', 'done', 1, 1, 49, '2025-11-08 08:42:20'),
(472, 218, 'registered', NULL, 'waiting', 1, 1, 48, '2025-11-08 14:22:26'),
(473, 218, 'status_changed', 'waiting', 'in consultation', 1, 1, 49, '2025-11-08 14:22:42'),
(474, 218, 'status_changed', 'in consultation', 'done', 1, 1, 49, '2025-11-08 14:22:43'),
(475, 219, 'registered', NULL, 'waiting', 1, 1, 48, '2025-11-08 14:23:23'),
(476, 222, 'status_changed', 'waiting', 'in consultation', 1, 1, 49, '2025-11-08 14:34:07'),
(477, 222, 'status_changed', 'in consultation', 'done', 1, 1, 49, '2025-11-08 14:34:08'),
(478, 223, 'registered', NULL, 'waiting', 2, 3, 48, '2025-11-08 17:24:32');

-- --------------------------------------------------------

--
-- Table structure for table `user_staff`
--

CREATE TABLE `user_staff` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `role` enum('staff','receptionist','admin') NOT NULL DEFAULT 'staff',
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `lockout_until` datetime DEFAULT NULL,
  `twofa_secret` varchar(255) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `ip_whitelist` text DEFAULT NULL,
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_token_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_staff`
--

INSERT INTO `user_staff` (`id`, `name`, `username`, `email`, `password`, `department_id`, `role`, `failed_attempts`, `lockout_until`, `twofa_secret`, `last_login`, `ip_whitelist`, `reset_token`, `reset_token_expiry`) VALUES
(23, 'System Administrator', 'admin', '', '$2y$10$C14t7TuTRzqvUMhDwybgtO0kcsN8cIm7YdmWzk6jli4OpKyKx0Mme', NULL, 'admin', 0, NULL, NULL, '2025-11-09 00:32:34', NULL, NULL, NULL),
(24, 'System Administrator', 'admin', '', '$2y$10$C14t7TuTRzqvUMhDwybgtO0kcsN8cIm7YdmWzk6jli4OpKyKx0Mme', NULL, 'admin', 0, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 'Cody Buenaventura', 'codyyj', '', '$2y$10$KDH2LLMsTOHSo/jNb9wqDuW7E2YsbmdaqO1hPNFsHkTpbnFqjxSl2', NULL, 'receptionist', 0, NULL, NULL, '2025-11-09 01:40:42', NULL, NULL, NULL),
(49, 'Jaz Salazar', 'west', '', '$2y$10$FVDmaH43V.wWW5ALqL6qeOTh.W2EIjcLumNcU.I6OjWBqrSB6gL32', 1, 'staff', 0, NULL, NULL, '2025-11-09 01:25:40', NULL, NULL, NULL),
(50, 'cody salazar', 'cody', '', '$2y$10$pKfYCdoaKdpppPWNG098euhM8by1oejcIGmktd5sJc9cS4ed3bYtu', 1, 'staff', 0, NULL, NULL, '2025-11-05 13:55:01', NULL, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_audit_log`
--
ALTER TABLE `admin_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `department_staff`
--
ALTER TABLE `department_staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `fk_patients_department_staff` (`department_staff_id`);

--
-- Indexes for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `queue_history`
--
ALTER TABLE `queue_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `staff_id` (`staff_id`),
  ADD KEY `fk_queue_history_department_staff` (`department_staff_id`);

--
-- Indexes for table `user_staff`
--
ALTER TABLE `user_staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_staff_department` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_audit_log`
--
ALTER TABLE `admin_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=349;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `department_staff`
--
ALTER TABLE `department_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=224;

--
-- AUTO_INCREMENT for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `queue_history`
--
ALTER TABLE `queue_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=479;

--
-- AUTO_INCREMENT for table `user_staff`
--
ALTER TABLE `user_staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_audit_log`
--
ALTER TABLE `admin_audit_log`
  ADD CONSTRAINT `admin_audit_log_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `user_staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `department_staff`
--
ALTER TABLE `department_staff`
  ADD CONSTRAINT `department_staff_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `fk_patients_department_staff` FOREIGN KEY (`department_staff_id`) REFERENCES `department_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `patient_vitals`
--
ALTER TABLE `patient_vitals`
  ADD CONSTRAINT `patient_vitals_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `queue_history`
--
ALTER TABLE `queue_history`
  ADD CONSTRAINT `fk_queue_history_department_staff` FOREIGN KEY (`department_staff_id`) REFERENCES `department_staff` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `queue_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `queue_history_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `queue_history_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `user_staff` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
