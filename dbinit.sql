-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 05, 2024 at 05:40 AM
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
-- Database: `sems`
--

CREATE DATABASE IF NOT EXISTS sems;

USE sems;

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `clock_in_time` time DEFAULT NULL,
  `clock_out_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `date`, `clock_in_time`, `clock_out_time`) VALUES
(1, 1, '2023-06-01', '08:00:00', '17:00:00'),
(2, 1, '2023-06-02', '08:05:00', '17:10:00'),
(3, 2, '2023-06-01', '08:30:00', '17:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `leaverequests`
--

CREATE TABLE `leaverequests` (
  `leave_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `LeaveType` enum('casual','sick','earned') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('Pending','Approved','Rejected') NOT NULL DEFAULT 'Pending',
  `requested_on` date NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaverequests`
--

INSERT INTO `leaverequests` (`leave_id`, `user_id`, `LeaveType`, `start_date`, `end_date`, `reason`, `status`, `requested_on`, `description`) VALUES
(1, 1, 'casual', '2024-07-01', '2024-07-10', 'Family vacation', 'Approved', '2024-06-15', 'accepted'),
(2, 2, 'sick', '2024-08-01', '2024-08-05', 'Medical leave', 'Approved', '2024-07-20', 'Recovering from surgery.'),
(3, 3, 'earned', '2024-09-15', '2024-09-25', 'Annual leave', 'Approved', '2024-08-10', 'Aprove'),
(4, 1, 'sick', '2024-07-20', '2024-07-25', 'Flu recovery', 'Rejected', '2024-07-18', 'Need time off to recover from the flu.'),
(5, 2, 'casual', '2024-06-20', '2024-06-25', 'Personal reasons', 'Approved', '2024-06-01', 'Personal matters to attend to.'),
(6, 3, 'earned', '2024-10-05', '2024-10-15', 'Holiday', 'Rejected', '2024-09-01', 'reject'),
(7, 1, 'sick', '2024-07-30', '2024-08-02', 'Dental surgery', 'Rejected', '2024-07-25', 'rejected'),
(8, 2, 'casual', '2024-08-10', '2024-08-15', 'Short vacation', 'Pending', '2024-07-30', 'Weekend getaway.'),
(9, 3, 'sick', '2024-09-01', '2024-09-03', 'Migraine', 'Approved', '2024-08-28', 'Severe migraine attacks.'),
(10, 1, 'earned', '2024-11-20', '2024-11-30', 'Long vacation', 'Pending', '2024-10-15', 'Extended holiday break.');

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `project_id` int(11) NOT NULL,
  `project_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending',
  `user_id` int(11) DEFAULT NULL,
  `managed_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_name`, `description`, `start_date`, `end_date`, `status`, `user_id`, `managed_by`) VALUES
(1, 'Website Redesign', 'Redesign the company website for better UX', '2023-06-01', '2023-12-01', 'In Progress', 14, NULL),
(2, 'Mobile App Development', 'Develop a mobile application for the company services', '2023-01-01', '2023-09-30', 'Pending', 2, NULL),
(3, 'project x', 'app development', '2024-07-04', '2024-07-31', 'Pending', NULL, NULL),
(4, 'project z', 'web', '2024-07-04', '2024-07-31', 'Pending', NULL, NULL),
(5, 'project ABC', 'Web Dev', '2024-07-04', '2024-07-12', 'Pending', NULL, NULL),
(6, 'project q', 'q', '2024-07-04', '2024-07-31', 'Pending', NULL, NULL),
(7, 'project r', 'rrrrrrrrrrr', '2024-07-04', '2024-07-31', 'Pending', NULL, NULL),
(8, 'project v', 'vvvv', '2024-07-04', '2024-07-31', 'Pending', NULL, 14),
(9, 'project qq', 'qqq', '2024-07-04', '2024-07-31', 'Pending', NULL, 14),
(10, 'project t', 'tt', '2024-07-04', '2024-07-31', 'Pending', 14, NULL),
(11, 'Project CDE', 'CDE', '2024-07-04', '2024-07-31', 'Pending', 14, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `salary_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL,
  `pay_period_start` date DEFAULT NULL,
  `pay_period_end` date DEFAULT NULL,
  `project_name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`salary_id`, `user_id`, `amount`, `payment_date`, `pay_period_start`, `pay_period_end`, `project_name`) VALUES
(1, 1, 5000.00, '2023-06-30', '2023-06-01', '2023-06-30', 'Website Redesign'),
(2, 2, 7000.00, '2023-06-30', '2023-06-01', '2023-06-30', 'Mobile App Development');

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `task_id` int(11) NOT NULL,
  `project_id` int(11) DEFAULT NULL,
  `task_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `assigned_to` int(11) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('Pending','In Progress','Completed','Not Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `project_id`, `task_name`, `description`, `assigned_to`, `start_date`, `end_date`, `status`) VALUES
(1, 1, 'Update Logo', 'Update the company logo with the new design', 1, '2023-06-15', '2023-06-30', 'Completed'),
(2, 1, 'Create Footer Design', 'Design the footer for the new website layout', 1, '2023-07-01', '2023-07-15', 'Pending'),
(3, 10, 'Design App UI', 'Design the user interface for the mobile app', 1, '2023-02-01', '2023-03-15', 'Pending'),
(4, 11, 'Develop A', 'Develop a', 1, '2024-07-01', '2024-07-20', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date_joined` date DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `emergency_contact_name` varchar(100) DEFAULT NULL,
  `emergency_contact_phone` varchar(15) DEFAULT NULL,
  `emergency_contact_relation` varchar(100) DEFAULT NULL,
  `project_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `first_name`, `last_name`, `email`, `phone`, `address`, `date_joined`, `department`, `job_title`, `date_of_birth`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `project_id`) VALUES
(1, 'jdoe', 'password123', 'employee', 'John', 'Doe', 'john.doe@example.com', '555-1234', '123 Elm Street', '2020-01-15', 'Development', 'Developer', '1990-05-15', 'Jane Doe', '555-5678', 'Spouse', 3),
(2, 'asmith', 'password456', 'project manager', 'Alice', 'Smith', 'alice.smith@example.com', '555-5678', '456 Oak Street', '2019-03-12', 'Development', 'Project Manager', '1985-09-20', 'Bob Smith', '555-8765', 'Husband', 2),
(3, 'mbrown', 'password789', 'HR', 'Mary', 'Brown', 'mary.brown@example.com', '555-8765', '789 Pine Street', '2021-06-22', 'Human Resources', 'HR Manager', '1988-11-30', 'Tom Brown', '555-3456', 'Brother', 1),
(4, 'ann45', '77wP6sL5+s', 'Manager', 'Sandra', 'Watson', 'tarasanders@guerra-norris.com', '387.753.2334', '6822 William Turnpike Suite 100\nWest Debra, MT 01367', '2023-10-14', 'IT', 'Coordinator', '1990-09-07', 'Philip Cox', '(087)086-2483', 'Spouse', 3),
(5, 'sanchezchristopher', '^&2NhhSy*c', 'Manager', 'Penny', 'Huang', 'alexisbeasley@yahoo.com', '+1-343-681-7053', '0045 Oscar Inlet Apt. 634\nEast Todd, TN 76406', '2023-08-01', 'IT', 'Coordinator', '1991-04-05', 'Catherine Lawrence', '338.382.8214x36', 'Parent', 3),
(6, 'jamesbrown', 'wz6V&SAh1$', 'Employee', 'Tracy', 'Cortez', 'johnsonmonica@simpson.com', '390-579-7472x06', '4268 Brian Bypass Suite 489\nPort Stephen, LA 12358', '2021-12-27', 'IT', 'Consultant', '1992-11-24', 'Regina Cox', '704.172.3227x81', 'Parent', 3),
(7, 'katelyn26', '@V(6k5NnIV', 'Manager', 'Alice', 'Blanchard', 'timothy82@hotmail.com', '(536)638-8550', '68378 Jones Falls Suite 746\nDeborahside, MT 38539-3078', '2020-08-03', 'HR', 'Manager', '1997-06-19', 'Jessica Lee', '7412843022', 'Sibling', 4),
(8, 'thomas48', '*0yMda6Zy9', 'Employee', 'Douglas', 'Adams', 'sarah08@clark-nguyen.com', '+1-797-989-0162', '005 Martinez Pike\nPort Allisonborough, MT 95646', '2021-08-13', 'Sales', 'Analyst', '1989-04-03', 'Nathan Dunn', '001-369-417-100', 'Sibling', 5),
(9, 'michaeljordan', 'zB2%6ht8L$', 'Employee', 'Michael', 'Jordan', 'michael.jordan@example.com', '123-456-7890', '123 Main St\nSpringfield, IL 62704', '2022-01-15', 'Marketing', 'Specialist', '1985-06-14', 'Sarah Jordan', '234-567-8901', 'Spouse', 3),
(10, 'emilyclark', 'P4ssW0rd!7$', 'Manager', 'Emily', 'Clark', 'emily.clark@example.com', '789-012-3456', '456 Elm St\nBrooklyn, NY 11201', '2023-03-05', 'Finance', 'Director', '1990-12-10', 'John Clark', '890-123-4567', 'Parent', 4),
(11, 'johnsmith', 'Qw3rtY&78', 'Employee', 'John', 'Smith', 'john.smith@example.com', '456-789-0123', '789 Pine St\nSeattle, WA 98101', '2021-06-20', 'Engineering', 'Engineer', '1987-08-22', 'Jane Smith', '567-890-1234', 'Sibling', 4),
(12, 'laurawilson', 'H3ll0W0rld$', 'Employee', 'Laura', 'Wilson', 'laura.wilson@example.com', '234-567-8901', '987 Oak St\nSan Francisco, CA 94102', '2022-07-30', 'HR', 'Recruiter', '1993-04-12', 'Paul Wilson', '345-678-9012', 'Parent', 8),
(13, 'davidlee', 'R3d@ppl3#8', 'Employee', 'David', 'Lee', 'david.lee@example.com', '678-901-2345', '321 Birch St\nBoston, MA 02118', '2021-04-11', 'Operations', 'Coordinator', '1991-11-05', 'Linda Lee', '789-012-3456', 'Spouse', 10),
(14, 'userone@mail.com', '$2y$10$TyVjOXMSbiBXTqzup54Yd.GkQG/XtVg82X6xOnSo.0BHRMhjqjJq6', 'project manager', 'user', 'one', 'userone@mail.com', '1111111111', 'address 5', '2024-07-01', 'Front end', 'project manager', '2024-07-02', NULL, NULL, NULL, NULL),
(15, 'empone@mail.com', '$2y$10$jg9JANbRXR8yypI2nakXGeMnFkUCRsTmCpZcuPJO4IuAGlmsKCE/2', 'employee', 'emp', 'one', 'empone@mail.com', '1111111111', 'address 2', '2024-07-01', 'back end', 'developer', '2024-07-01', NULL, NULL, NULL, 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`attendance_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `user_id` (`user_id`);


--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`salary_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `project_id` (`project_id`),
  ADD KEY `assigned_to` (`assigned_to`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_project_id` (`project_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `leaverequests`
--
ALTER TABLE `leaverequests`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD CONSTRAINT `leaverequests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `projects_ibfk_2` FOREIGN KEY (`managed_by`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `tasks_ibfk_1` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `tasks_ibfk_2` FOREIGN KEY (`assigned_to`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`project_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
