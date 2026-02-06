-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2026 at 07:35 AM
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
-- Database: `student_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `branch_id` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL,
  `school_id` int(11) NOT NULL,
  `branch_code` varchar(10) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_on` datetime DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `updated_on` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`branch_id`, `id`, `school_id`, `branch_code`, `branch_name`, `status`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
(1, 1, 1, 'B1', 'Branch 1', 1, '2025-12-05 21:13:26', 'user1', '2025-12-08 01:10:14', NULL),
(2, 2, 1, 'B2', 'Branch 2', 1, '2025-12-05 21:13:26', NULL, '2025-12-08 01:10:26', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `class_name` varchar(50) NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `time_slot_id` int(11) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `created_by` varchar(50) DEFAULT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `status` tinyint(1) DEFAULT 1,
  `updated_on` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `school_id` varchar(10) NOT NULL,
  `branch_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `class_name`, `teacher_id`, `time_slot_id`, `name`, `created_by`, `created_on`, `status`, `updated_on`, `updated_by`, `school_id`, `branch_id`) VALUES
(38, 'tamil', 2, 6, NULL, NULL, '2025-12-15 21:28:26', 1, '2025-12-15 21:28:26', NULL, '', '2'),
(39, 'tamil', 1, 5, NULL, NULL, '2025-12-15 21:35:53', 1, '2025-12-15 21:35:53', NULL, '', '1');

-- --------------------------------------------------------

--
-- Table structure for table `class_assignments`
--

CREATE TABLE `class_assignments` (
  `id` int(11) NOT NULL,
  `roll_number` varchar(50) NOT NULL,
  `student_name` varchar(100) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `branch_name` varchar(50) NOT NULL,
  `class_name` varchar(100) NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_on` datetime DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_on` datetime DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `assigned_on` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `class_assignments`
--

INSERT INTO `class_assignments` (`id`, `roll_number`, `student_name`, `branch_id`, `branch_name`, `class_name`, `status`, `created_by`, `created_on`, `updated_by`, `updated_on`, `student_id`, `assigned_on`) VALUES
(1, '1THSSBRANCH1', 'ganga', 1, '', 'English', 1, NULL, '2025-12-11 21:13:26', NULL, NULL, 27, '2025-12-11 21:13:26'),
(111, '', '', 1, '', '', 1, NULL, NULL, NULL, NULL, 44, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `gender`
--

CREATE TABLE `gender` (
  `id` int(11) NOT NULL,
  `gender_name` varchar(20) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gender`
--

INSERT INTO `gender` (`id`, `gender_name`, `status`) VALUES
(1, 'Male', 1),
(2, 'Female', 1);

-- --------------------------------------------------------

--
-- Table structure for table `schools`
--

CREATE TABLE `schools` (
  `id` int(11) NOT NULL,
  `school_id` varchar(10) NOT NULL,
  `branch_id` varchar(10) NOT NULL,
  `school_name` varchar(100) DEFAULT NULL,
  `school_code` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `schools`
--

INSERT INTO `schools` (`id`, `school_id`, `branch_id`, `school_name`, `school_code`, `address`, `name`, `status`, `created_on`, `created_by`, `updated_on`, `updated_by`) VALUES
(1, '1', '1', 'Thambu', 'MHSS', 'Coimbatore', '', 1, '2025-12-05 21:53:19', 0, '2025-12-15 03:08:00', 0);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `school_id` int(11) NOT NULL,
  `branch_id` int(11) NOT NULL,
  `roll_number` varchar(50) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_on` datetime NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL DEFAULT 0,
  `updated_on` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) NOT NULL DEFAULT 0,
  `subject1` varchar(50) DEFAULT NULL,
  `subject2` varchar(50) DEFAULT NULL,
  `subject3` varchar(50) DEFAULT NULL,
  `subject4` varchar(50) DEFAULT NULL,
  `branch_code` varchar(10) DEFAULT NULL,
  `branch_name` varchar(10) DEFAULT NULL,
  `gender_id` int(11) DEFAULT NULL,
  `delete_reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_name`, `school_id`, `branch_id`, `roll_number`, `status`, `created_on`, `created_by`, `updated_on`, `updated_by`, `subject1`, `subject2`, `subject3`, `subject4`, `branch_code`, `branch_name`, `gender_id`, `delete_reason`) VALUES
(44, 'ganga', 1, 1, '1MHSS', 1, '2025-12-15 21:03:58', 1, '2025-12-15 21:03:58', 0, '78', '89', '67', '78', 'B1', 'Branch 1', 2, NULL),
(45, 'aryan', 1, 2, '1MHSS', 1, '2025-12-15 21:04:28', 2, '2025-12-15 21:04:28', 0, '78', '89', '67', '89', 'B2', 'Branch 2', 1, NULL),
(46, 'meha', 1, 1, '2MHSS', 1, '2025-12-15 21:53:35', 1, '2025-12-15 21:53:35', 0, '78', '98', '90', '78', 'B1', 'Branch 1', 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` int(11) NOT NULL,
  `teacher_name` varchar(100) NOT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `status` varchar(10) DEFAULT 'Active',
  `created_on` datetime DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `updated_on` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `school_id` varchar(10) NOT NULL,
  `branch_id` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `teacher_name`, `phone_number`, `status`, `created_on`, `created_by`, `updated_on`, `updated_by`, `school_id`, `branch_id`) VALUES
(1, 'John Doe', '9999999999', 'Active', '2025-12-04 03:16:16', NULL, '2025-12-07 19:59:08', NULL, '1', '1'),
(2, 'Jane Smith', '8888888888', 'Active', '2025-12-04 03:16:16', NULL, '2025-12-08 02:14:07', NULL, '1', '2');

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int(11) NOT NULL,
  `slot_name` varchar(100) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_on` datetime DEFAULT current_timestamp(),
  `created_by` varchar(50) DEFAULT NULL,
  `updated_on` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` varchar(50) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 1,
  `branch_id` int(11) NOT NULL,
  `branch_code` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `slot_name`, `start_time`, `end_time`, `created_on`, `created_by`, `updated_on`, `updated_by`, `status`, `branch_id`, `branch_code`) VALUES
(5, '08:00 AM - 09:00 AM', '00:00:00', '00:00:00', '2025-12-09 06:31:29', NULL, '2025-12-10 23:40:27', NULL, 0, 1, 'B1'),
(6, '09:00 AM - 10:00 AM', '00:00:00', '00:00:00', '2025-12-09 06:31:29', NULL, '2025-12-10 23:40:44', NULL, 0, 2, 'B2');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_on` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_by` int(11) NOT NULL,
  `updated_on` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_by` int(11) DEFAULT NULL,
  `branch_id` int(11) NOT NULL,
  `school_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `status`, `created_on`, `created_by`, `updated_on`, `updated_by`, `branch_id`, `school_id`) VALUES
(1, 'branch1', '$2y$10$8Z/.I.R6v9mfESw5JSTzBOowBRrAQY4FK4U82I1mupvKkJC0eQ79W', 'active', '2025-12-06 08:49:33', 0, '2025-12-08 07:46:47', NULL, 1, 1),
(2, 'branch2', '$2y$10$x3CXzHZoIgsRh9zmOtOXp.tUmGECvvBqiVS2GnffCD06gkP6vna7y', 'active', '2025-12-08 07:19:54', 0, '2025-12-08 08:31:06', NULL, 2, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teacher_id` (`teacher_id`),
  ADD KEY `time_slot_id` (`time_slot_id`);

--
-- Indexes for table `class_assignments`
--
ALTER TABLE `class_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `gender`
--
ALTER TABLE `gender`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `schools`
--
ALTER TABLE `schools`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `school_id` (`school_id`),
  ADD KEY `branch_id` (`branch_id`),
  ADD KEY `fk_gender` (`gender_id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `time_slots`
--
ALTER TABLE `time_slots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `class_assignments`
--
ALTER TABLE `class_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `gender`
--
ALTER TABLE `gender`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `schools`
--
ALTER TABLE `schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `time_slots`
--
ALTER TABLE `time_slots`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `branches`
--
ALTER TABLE `branches`
  ADD CONSTRAINT `branches_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`);

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`),
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`time_slot_id`) REFERENCES `time_slots` (`id`);

--
-- Constraints for table `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `fk_gender` FOREIGN KEY (`gender_id`) REFERENCES `gender` (`id`),
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`),
  ADD CONSTRAINT `students_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
