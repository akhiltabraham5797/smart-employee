-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2024 at 07:34 AM
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

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `attendance_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `work_hours` int(11) NOT NULL,
  `clock_in_time` time DEFAULT NULL,
  `clock_out_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `date`, `work_hours`, `clock_in_time`, `clock_out_time`) VALUES
(1, 1, '2023-06-01', 0, '08:00:00', '17:00:00'),
(2, 1, '2023-06-02', 0, '08:05:00', '17:10:00'),
(3, 2, '2023-06-01', 0, '08:30:00', '17:30:00'),
(4, 17, '2024-07-25', 0, '01:42:21', '01:56:14'),
(5, 17, '2024-07-25', 0, '01:58:06', '02:15:06'),
(6, 17, '2024-07-25', 0, '02:15:18', NULL),
(7, 15, '2024-07-25', 0, '06:04:48', '06:12:11'),
(8, 14, '2024-07-25', 0, '06:06:00', '06:12:42'),
(9, 16, '2024-07-25', 0, '06:06:10', NULL),
(10, 15, '2024-07-25', 0, '06:07:53', '06:12:11'),
(11, 15, '2024-07-25', 0, '06:11:38', '06:12:11'),
(12, 14, '2024-07-25', 0, '06:12:29', '06:12:42'),
(13, 16, '2024-07-25', 0, '06:12:53', NULL),
(14, 14, '2024-07-25', 0, '06:31:39', NULL),
(15, 16, '2024-07-25', 0, '06:32:13', NULL),
(16, 15, '2024-07-25', 0, '07:14:52', '14:17:27'),
(17, 15, '2024-07-25', 0, '07:18:13', NULL),
(18, 16, '2024-07-25', 0, '07:26:40', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `compaint_id` bigint(20) NOT NULL,
  `user_id` int(11) NOT NULL,
  `complaint_description` longtext NOT NULL,
  `raised_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`compaint_id`, `user_id`, `complaint_description`, `raised_on`) VALUES
(1, 1, 'testing123', '2024-07-20 22:30:00'),
(2, 1, 'ttttttttttttttttttt', '2024-07-20 22:30:00'),
(3, 1, 'test compllllllllllllllll', '2024-07-20 22:30:00'),
(4, 24, 'late salary', '2024-07-24 08:00:00');

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
  `status` enum('Pending','Approved by PM','Approved by HR','Rejected') NOT NULL DEFAULT 'Pending',
  `requested_on` date NOT NULL,
  `description` text DEFAULT NULL,
  `approved_by` int(11) DEFAULT NULL,
  `approval_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leaverequests`
--

INSERT INTO `leaverequests` (`leave_id`, `user_id`, `LeaveType`, `start_date`, `end_date`, `reason`, `status`, `requested_on`, `description`, `approved_by`, `approval_date`) VALUES
(1, 1, 'casual', '2024-07-01', '2024-07-10', 'Family vacation', '', '2024-06-15', 'accepted', NULL, NULL),
(2, 2, 'sick', '2024-08-01', '2024-08-05', 'Medical leave', '', '2024-07-20', 'Recovering from surgery.', NULL, NULL),
(3, 3, 'earned', '2024-09-15', '2024-09-25', 'Annual leave', '', '2024-08-10', 'Aprove', NULL, NULL),
(4, 1, 'sick', '2024-07-20', '2024-07-25', 'Flu recovery', 'Rejected', '2024-07-18', 'Need time off to recover from the flu.', NULL, NULL),
(5, 2, 'casual', '2024-06-20', '2024-06-25', 'Personal reasons', '', '2024-06-01', 'Personal matters to attend to.', NULL, NULL),
(6, 3, 'earned', '2024-10-05', '2024-10-15', 'Holiday', 'Rejected', '2024-09-01', 'reject', NULL, NULL),
(7, 1, 'sick', '2024-07-30', '2024-08-02', 'Dental surgery', 'Rejected', '2024-07-25', 'rejected', NULL, NULL),
(8, 2, 'casual', '2024-08-10', '2024-08-15', 'Short vacation', 'Rejected', '2024-07-30', 'Weekend getaway.', 12, '2024-07-23'),
(9, 3, 'sick', '2024-09-01', '2024-09-03', 'Migraine', 'Rejected', '2024-08-28', 'Severe migraine attacks.', 12, '2024-07-23'),
(10, 1, 'earned', '2024-11-20', '2024-11-30', 'Long vacation', 'Approved by HR', '2024-10-15', 'Extended holiday break.', 12, '2024-07-23'),
(11, 15, 'casual', '2024-07-24', '2024-07-26', 'something', 'Rejected', '2024-07-25', 'yess', 16, '2024-07-25');

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
  `project_name` varchar(100) DEFAULT NULL,
  `hourly_rate` decimal(10,2) NOT NULL,
  `total_hours` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`salary_id`, `user_id`, `amount`, `payment_date`, `pay_period_start`, `pay_period_end`, `project_name`, `hourly_rate`, `total_hours`) VALUES
(3, 1, 2000.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project A', 25.00, 80.00),
(4, 2, 2500.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project B', 30.00, 83.33),
(5, 3, 1800.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project C', 20.00, 90.00),
(6, 4, 2200.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project D', 27.50, 80.00),
(7, 5, 2400.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project E', 28.00, 85.71),
(8, 6, 1920.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project F', 24.00, 80.00),
(9, 7, 1750.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project G', 21.88, 80.00),
(10, 8, 2100.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project H', 25.00, 84.00),
(11, 9, 2300.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project I', 28.75, 80.00),
(12, 10, 2800.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project J', 35.00, 80.00),
(13, 11, 2650.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project K', 33.13, 80.00),
(14, 12, 1900.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project L', 23.75, 80.00),
(15, 13, 2100.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project M', 26.25, 80.00),
(16, 14, 2900.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project N', 36.25, 80.00),
(17, 15, 3200.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project O', 40.00, 80.00),
(18, 16, 2700.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project P', 33.75, 80.00),
(19, 17, 3000.00, '2024-07-31', '2024-07-01', '2024-07-31', 'Project Q', 37.50, 80.00),
(20, 15, 144.33, '2024-07-25', '2024-07-01', '2024-07-31', NULL, 20.00, 7.22),
(21, 15, 146.80, '2024-07-25', '2024-07-09', '2024-07-31', NULL, 20.00, 7.34),
(22, 15, 147.00, '2024-07-25', '2024-07-09', '2024-07-31', NULL, 20.00, 7.35);

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
  `project_id` int(11) DEFAULT NULL,
  `hourly_rate` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `first_name`, `last_name`, `email`, `phone`, `address`, `date_joined`, `department`, `job_title`, `date_of_birth`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`, `project_id`, `hourly_rate`) VALUES
(1, 'jdoe', 'password123', 'employee', 'John', 'Doe', 'john.doe@example.com', '555-1234', '123 Elm Street', '2020-01-15', 'Development', 'Developer', '1990-05-15', 'Jane Doe', '555-5678', 'Spouse', 3, 0),
(2, 'asmith', 'password456', 'project manager', 'Alice', 'Smith', 'alice.smith@example.com', '555-5678', '456 Oak Street', '2019-03-12', 'Development', 'Project Manager', '1985-09-20', 'Bob Smith', '555-8765', 'Husband', 2, 0),
(3, 'mbrown', 'password789', 'HR', 'Mary', 'Brown', 'mary.brown@example.com', '555-8765', '789 Pine Street', '2021-06-22', 'Human Resources', 'HR Manager', '1988-11-30', 'Tom Brown', '555-3456', 'Brother', 1, 0),
(4, 'ann45', '77wP6sL5+s', 'Manager', 'Sandra', 'Watson', 'tarasanders@guerra-norris.com', '387.753.2334', '6822 William Turnpike Suite 100\nWest Debra, MT 01367', '2023-10-14', 'IT', 'Coordinator', '1990-09-07', 'Philip Cox', '(087)086-2483', 'Spouse', 3, 0),
(5, 'sanchezchristopher', '^&2NhhSy*c', 'Manager', 'Penny', 'Huang', 'alexisbeasley@yahoo.com', '+1-343-681-7053', '0045 Oscar Inlet Apt. 634\nEast Todd, TN 76406', '2023-08-01', 'IT', 'Coordinator', '1991-04-05', 'Catherine Lawrence', '338.382.8214x36', 'Parent', 3, 0),
(6, 'jamesbrown', 'wz6V&SAh1$', 'Employee', 'Tracy', 'Cortez', 'johnsonmonica@simpson.com', '390-579-7472x06', '4268 Brian Bypass Suite 489\nPort Stephen, LA 12358', '2021-12-27', 'IT', 'Consultant', '1992-11-24', 'Regina Cox', '704.172.3227x81', 'Parent', 3, 0),
(7, 'katelyn26', '@V(6k5NnIV', 'Manager', 'Alice', 'Blanchard', 'timothy82@hotmail.com', '(536)638-8550', '68378 Jones Falls Suite 746\nDeborahside, MT 38539-3078', '2020-08-03', 'HR', 'Manager', '1997-06-19', 'Jessica Lee', '7412843022', 'Sibling', 4, 0),
(8, 'thomas48', '*0yMda6Zy9', 'Employee', 'Douglas', 'Adams', 'sarah08@clark-nguyen.com', '+1-797-989-0162', '005 Martinez Pike\nPort Allisonborough, MT 95646', '2021-08-13', 'Sales', 'Analyst', '1989-04-03', 'Nathan Dunn', '001-369-417-100', 'Sibling', 5, 0),
(9, 'michaeljordan', 'zB2%6ht8L$', 'Employee', 'Michael', 'Jordan', 'michael.jordan@example.com', '123-456-7890', '123 Main St\nSpringfield, IL 62704', '2022-01-15', 'Marketing', 'Specialist', '1985-06-14', 'Sarah Jordan', '234-567-8901', 'Spouse', 3, 0),
(10, 'emilyclark', 'P4ssW0rd!7$', 'Manager', 'Emily', 'Clark', 'emily.clark@example.com', '789-012-3456', '456 Elm St\nBrooklyn, NY 11201', '2023-03-05', 'Finance', 'Director', '1990-12-10', 'John Clark', '890-123-4567', 'Parent', 4, 0),
(11, 'johnsmith', 'Qw3rtY&78', 'Employee', 'John', 'Smith', 'john.smith@example.com', '456-789-0123', '789 Pine St\nSeattle, WA 98101', '2021-06-20', 'Engineering', 'Engineer', '1987-08-22', 'Jane Smith', '567-890-1234', 'Sibling', 4, 0),
(12, 'laurawilson', 'H3ll0W0rld$', 'Employee', 'Laura', 'Wilson', 'laura.wilson@example.com', '234-567-8901', '987 Oak St\nSan Francisco, CA 94102', '2022-07-30', 'HR', 'Recruiter', '1993-04-12', 'Paul Wilson', '345-678-9012', 'Parent', 8, 0),
(13, 'davidlee', 'R3d@ppl3#8', 'Employee', 'David', 'Lee', 'david.lee@example.com', '678-901-2345', '321 Birch St\nBoston, MA 02118', '2021-04-11', 'Operations', 'Coordinator', '1991-11-05', 'Linda Lee', '789-012-3456', 'Spouse', 10, 0),
(14, 'userone@mail.com', '$2y$10$TyVjOXMSbiBXTqzup54Yd.GkQG/XtVg82X6xOnSo.0BHRMhjqjJq6', 'project manager', 'user', 'one', 'userone@mail.com', '1111111111', 'address 5', '2024-07-01', 'Front end', 'project manager', '2024-07-02', NULL, NULL, NULL, NULL, 0),
(15, 'empone@mail.com', '$2y$10$jg9JANbRXR8yypI2nakXGeMnFkUCRsTmCpZcuPJO4IuAGlmsKCE/2', 'employee', 'emp', 'one', 'empone@mail.com', '1111111111', 'address 2', '2024-07-01', 'back end', 'developer', '2024-07-01', NULL, NULL, NULL, 4, 20),
(16, 'ob@mail.com', '$2y$10$WEiofwoUwxCzyc6KuX4r6uaXK/g00iaCuTL0J8Vy9UTC58H5.KGMG', 'HR', 'Bob', 'Brown', 'ob@mail.com', '1111111111', 'address123', '2020-08-01', 'HR', 'HR', '1996-01-01', NULL, NULL, NULL, NULL, 0),
(17, 'anaij@mail.com', '$2y$10$xR8muB7UXejd.9X0jPU6neEKoifzeOrv8BYno8tKG70Js9SAN7Mti', 'employee', 'anai', 'joshy', 'anaij@mail.com', '1234567890', 'address231', '2024-07-01', 'development', 'employee', '2000-02-08', NULL, NULL, NULL, NULL, 0);

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
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`compaint_id`);

--
-- Indexes for table `leaverequests`
--
ALTER TABLE `leaverequests`
  ADD PRIMARY KEY (`leave_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `approved_by` (`approved_by`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `managed_by` (`managed_by`);

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
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `compaint_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `leaverequests`
--
ALTER TABLE `leaverequests`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
  ADD CONSTRAINT `leaverequests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `leaverequests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`user_id`);

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
