-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 03:16 AM
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
(2, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 03:41:44'),
(3, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 03:42:08'),
(4, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 03:42:34'),
(5, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 03:46:14'),
(6, 23, 'account_locked', 'Account locked due to failed attempts', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 03:46:38'),
(7, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 03:49:06'),
(8, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 03:56:45'),
(9, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:12:56'),
(10, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:13:05'),
(11, 23, 'staff_deleted', 'Deleted staff account ID: 21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:13:23'),
(12, 23, 'staff_deleted', 'Deleted staff account ID: 20', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:13:42'),
(13, 23, 'staff_deleted', 'Deleted staff account ID: 22', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:13:53'),
(14, 23, 'staff_deleted', 'Deleted staff account ID: 19', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:14:04'),
(15, 23, 'staff_deleted', 'Deleted staff account ID: 21', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:14:56'),
(16, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:22:30'),
(17, 23, 'staff_deleted', 'Deleted staff account ID: 25', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:23:31'),
(18, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:27:29'),
(19, 23, 'staff_updated', 'Updated staff account: jinu', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:28:09'),
(20, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 04:41:49'),
(26, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:25:55'),
(27, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:26:04'),
(28, 23, 'staff_deleted', 'Deleted staff account ID: 26', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:26:09'),
(29, 23, 'staff_deleted', 'Deleted staff account ID: 27', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:26:14'),
(30, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:28:25'),
(31, 23, 'staff_deleted', 'Deleted staff account ID: 28', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 05:28:27'),
(32, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 07:29:10'),
(33, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 10:48:28'),
(34, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 10:48:37'),
(35, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-20 11:11:09'),
(66, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:39:10'),
(67, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:39:17'),
(68, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:39:32'),
(69, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:40:42'),
(74, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:48:03'),
(75, 23, 'staff_deleted', 'Deleted staff account ID: 34', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:48:59'),
(76, 23, 'staff_deleted', 'Deleted staff account ID: 30', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:49:03'),
(77, 23, 'staff_deleted', 'Deleted staff account ID: 35', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:49:07'),
(78, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-23 07:49:32'),
(199, 23, 'login_failed', 'Invalid password', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:25:56'),
(200, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:26:04'),
(201, 23, 'staff_deleted', 'Deleted staff account ID: 39', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:26:16'),
(202, 23, 'staff_deleted', 'Deleted staff account ID: 38', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:26:19'),
(203, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 13:26:23'),
(239, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 14:43:50'),
(240, 23, 'staff_deleted', 'Deleted staff account ID: 40', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 14:43:57'),
(241, 23, 'staff_deleted', 'Deleted staff account ID: 41', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 14:44:00'),
(242, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 15:28:07'),
(256, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 16:19:58'),
(257, 23, 'staff_deleted', 'Deleted staff account ID: 43', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 16:20:03'),
(258, 23, 'staff_deleted', 'Deleted staff account ID: 42', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 16:20:05'),
(262, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 16:26:48'),
(269, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 16:46:55'),
(270, 23, 'logout', 'User logged out', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-29 16:47:53'),
(280, 23, 'login_success', 'Successful login', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-30 02:07:25'),
(281, 23, 'staff_deleted', 'Deleted staff account ID: 47', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-30 02:08:04'),
(282, 23, 'staff_deleted', 'Deleted staff account ID: 46', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36', '2025-10-30 02:08:07');

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
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `department_id` int(11) NOT NULL,
  `status` enum('available','unavailable') NOT NULL DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `name`, `department_id`, `status`) VALUES
(1, 'Dr. John Smith', 1, 'available'),
(2, 'Dr. Evelyn Reed', 1, 'available'),
(3, 'Dr. Michael Chen', 2, 'available'),
(4, 'Dr. Sarah Jones', 2, 'unavailable'),
(5, 'Dr. David Garcia', 3, 'available'),
(6, 'Dr. Maria Angela Cruz', 4, 'available'),
(7, 'Dr. Kristine Dela Peña', 4, 'available'),
(8, 'Dr. Liza Mendoza', 4, 'available'),
(9, 'Dr. Raymond Santos', 5, 'available'),
(10, 'Dr. Clarisse Go', 5, 'available'),
(11, 'Dr. Benedict Uy', 5, 'available'),
(12, 'Dr. Trisha Villanueva', 6, 'available'),
(13, 'Dr. Paolo Lim', 6, 'available'),
(14, 'Dr. Hannah Robles', 6, 'available');

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
  `doctor_id` int(11) DEFAULT NULL,
  `status` enum('waiting','called','in consultation','done','cancelled','no show') NOT NULL DEFAULT 'waiting',
  `check_in_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `birthdate` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `gender` enum('male','female','prefer not to say') DEFAULT NULL,
  `civil_status` enum('single','married','widow') DEFAULT NULL,
  `registration_datetime` datetime DEFAULT NULL,
  `bp` varchar(50) DEFAULT NULL,
  `temp` varchar(50) DEFAULT NULL,
  `cr_pr` varchar(50) DEFAULT NULL,
  `rr` varchar(50) DEFAULT NULL,
  `wt` varchar(50) DEFAULT NULL,
  `o2sat` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `first_name`, `middle_name`, `last_name`, `age`, `contact_number`, `reason_for_visit`, `parent_guardian`, `queue_number`, `department_id`, `doctor_id`, `status`, `check_in_time`, `created_at`, `birthdate`, `address`, `gender`, `civil_status`, `registration_datetime`, `bp`, `temp`, `cr_pr`, `rr`, `wt`, `o2sat`) VALUES
(200, 'Louie', 'G', 'Ogag', 22, '09567990016', 'Check-up', 'N/A', 1, 1, 1, 'waiting', '2025-10-29 17:29:01', '2025-10-29 17:29:01', '2003-05-07', 'Barangay Malopit', 'prefer not to say', 'widow', '2025-10-30 01:28:00', '', '', '', '', '', '');

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
  `doctor_id` int(11) DEFAULT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `queue_history`
--

INSERT INTO `queue_history` (`id`, `patient_id`, `action`, `old_status`, `new_status`, `department_id`, `doctor_id`, `staff_id`, `created_at`) VALUES
(423, 200, 'registered', NULL, 'waiting', 1, 1, NULL, '2025-10-29 17:29:01');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
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
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `name`, `username`, `email`, `password`, `department_id`, `role`, `failed_attempts`, `lockout_until`, `twofa_secret`, `last_login`, `ip_whitelist`, `reset_token`, `reset_token_expiry`) VALUES
(23, 'System Administrator', 'admin', '', '$2y$10$C14t7TuTRzqvUMhDwybgtO0kcsN8cIm7YdmWzk6jli4OpKyKx0Mme', NULL, 'admin', 0, NULL, NULL, '2025-10-30 10:07:25', NULL, NULL, NULL),
(24, 'System Administrator', 'admin', '', '$2y$10$C14t7TuTRzqvUMhDwybgtO0kcsN8cIm7YdmWzk6jli4OpKyKx0Mme', NULL, 'admin', 0, NULL, NULL, NULL, NULL, NULL, NULL);

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
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`);

--
-- Indexes for table `queue_history`
--
ALTER TABLE `queue_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `staff_id` (`staff_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_staff_department` (`department_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_audit_log`
--
ALTER TABLE `admin_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=283;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=201;

--
-- AUTO_INCREMENT for table `queue_history`
--
ALTER TABLE `queue_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=424;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_audit_log`
--
ALTER TABLE `admin_audit_log`
  ADD CONSTRAINT `admin_audit_log_ibfk_1` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `queue_history`
--
ALTER TABLE `queue_history`
  ADD CONSTRAINT `queue_history_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `queue_history_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `queue_history_ibfk_3` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
