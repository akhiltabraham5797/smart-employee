-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Aug 15, 2024 at 12:25 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

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
  `clock_in_time` time DEFAULT NULL,
  `clock_out_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`attendance_id`, `user_id`, `date`, `clock_in_time`, `clock_out_time`) VALUES
(1, 21, '2024-07-25', '14:01:27', '14:02:26'),
(2, 30, '2024-08-14', '23:02:10', '23:15:59'),
(3, 17, '2024-08-14', '23:17:15', NULL),
(4, 30, '2024-08-14', '23:19:39', NULL),
(5, 30, '2024-08-14', '23:28:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(150) NOT NULL,
  `category_shortcode` varchar(10) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`, `category_shortcode`, `create_date`) VALUES
(1, 'Head Phones', 'HP', '2024-08-14 14:40:18'),
(2, 'Smart Watches', 'SM', '2024-08-14 14:40:18'),
(3, 'Pen Drives', 'PD', '2024-08-14 14:40:26');

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
(4, 24, 'late salary', '2024-07-24 04:00:00'),
(5, 25, 'bad customer service', '2024-07-25 04:00:00'),
(6, 30, 'sdjhidsuhdsf', '2024-08-14 04:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `leaverequests`
--

CREATE TABLE `leaverequests` (
  `leave_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `requested_on` date NOT NULL,
  `approved_by_level1` int(11) DEFAULT NULL,
  `level1_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `level1_approved_on` date DEFAULT NULL,
  `approved_by_level2` int(11) DEFAULT NULL,
  `level2_status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `level2_approved_on` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `order_number` bigint(20) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `address` text NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` bigint(20) NOT NULL,
  `order_total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`order_id`, `user_id`, `order_number`, `create_date`, `first_name`, `last_name`, `address`, `email`, `phone`, `order_total`) VALUES
(0, 30, 1723669899, '2024-08-15 03:11:39', 'Abraham Charles', 'Thotekat Albey', '79', 'charlesalbey007@gmail.com', 2499890218, 10.00),
(1, 21, 1723662779, '2024-08-14 19:42:59', 'satheesh', 'kumar', 'tttttt', 'tttt@gggg.com', 9246422200, 127.26),
(2, 21, 1723663101, '2024-08-14 19:48:21', 'Satheesh', 'Poorella', 'Flat No 401  4th Floor', 'satheesh@vitelglobal.com', 7894561236, 19.98),
(3, 21, 1723664389, '2024-08-14 20:09:49', 'Testing', 'Poorella', 'Flat No 401  4th Floor', 'satheesh@vitelglobal.com', 5643534534, 171.98),
(4, 21, 1723664662, '2024-08-14 20:14:22', 'nnnnnnnnnn', 'rrrrrrrrrrr', 'Flat No 401  4th Floor', 'satheesh@vitelglobal.com', 45423423424, 1151.98);

-- --------------------------------------------------------

--
-- Table structure for table `order_details_products`
--

CREATE TABLE `order_details_products` (
  `prod_id` int(11) NOT NULL,
  `order_number` bigint(20) NOT NULL,
  `product_id` bigint(20) NOT NULL,
  `qty` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `order_details_products`
--

INSERT INTO `order_details_products` (`prod_id`, `order_number`, `product_id`, `qty`, `price`) VALUES
(0, 1723669899, 1, 1, 10.00),
(1, 1723662779, 2, 2, 9.98),
(2, 1723662779, 1, 1, 10.00),
(3, 1723662779, 4, 4, 8.95),
(4, 1723662779, 3, 3, 20.50),
(5, 1723663101, 1, 1, 10.00),
(6, 1723663101, 2, 1, 9.98),
(7, 1723664389, 2, 1, 9.98),
(8, 1723664389, 6, 1, 72.00),
(9, 1723664389, 7, 1, 90.00),
(10, 1723664662, 6, 1, 72.00),
(11, 1723664662, 7, 7, 90.00),
(12, 1723664662, 5, 5, 80.00),
(13, 1723664662, 8, 1, 40.00),
(14, 1723664662, 2, 1, 9.98);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_code` varchar(10) NOT NULL,
  `product_name` varchar(150) NOT NULL,
  `product_description` text DEFAULT NULL,
  `product_image` varchar(50) NOT NULL DEFAULT 'default.png',
  `product_price` decimal(10,2) NOT NULL,
  `category_id` int(11) NOT NULL,
  `create_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_code`, `product_name`, `product_description`, `product_image`, `product_price`, `category_id`, `create_date`) VALUES
(1, 'HP001', 'Head Phones Test 1', 'Head Phones Test 1 Description', '1.jpg', 10.00, 1, '2024-08-14 15:04:06'),
(2, 'HP002', 'Head Phones Test 2', 'Head Phones Test 2 Description', '2.jpg', 9.98, 1, '2024-08-14 15:04:06'),
(3, 'HP003', 'Head Phones Test 3', 'Head Phones Test 2 Description', '3.jpg', 20.50, 1, '2024-08-14 17:02:01'),
(4, 'HP004', 'Head Phones Test 4', 'Head Phones Test 4 description', 'default.png', 8.95, 1, '2024-08-14 17:02:01'),
(5, 'SM001', 'Smart Watch 1', 'Smart Watch 1 description', 'smartwatch1.jpg', 80.00, 2, '2024-08-14 17:02:01'),
(6, 'SM002', 'Smart Watch 2', 'Smart Watch 2 description', 'smartwatch2.jpg', 72.00, 2, '2024-08-14 17:02:01'),
(7, 'SM003', 'Smart Watch 3', 'Smart Watch 3 description', 'smartwatch3.jpg', 90.00, 2, '2024-08-14 17:02:01'),
(8, 'SM004', 'Smart Watch 4', 'Smart Watch 4 description', 'smartwatch4.jpg', 40.00, 2, '2024-08-14 17:02:01'),
(9, 'PD001', 'Pen Drive 1', 'Pen Drive 1 description', 'pendrive1.jpg', 6.00, 3, '2024-08-14 17:02:01'),
(10, 'PD002', 'Pen Drive 2', 'Pen Drive 2 description', 'pendrive2.jpg', 3.00, 3, '2024-08-14 17:02:01'),
(11, 'PD003', 'Pen Drive 3', 'Pen Drive 3 description', 'pendrive3.jpg', 15.00, 3, '2024-08-14 17:02:01'),
(12, 'PD004', 'Pen Drive 4', 'Pen Drive 4 description', 'pendrive4.jpg', 11.00, 3, '2024-08-14 17:02:01');

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
  `user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`project_id`, `project_name`, `description`, `start_date`, `end_date`, `status`, `user_id`) VALUES
(1, 'Front-end', 'Create login and sign up', '2024-01-01', '2024-10-10', 'In Progress', 15),
(2, 'fullstack', 'final project', '0000-00-00', '0000-00-00', '', 15),
(3, 'Banking Application', 'Create a payment gateway for the banking app', '0000-00-00', '0000-00-00', 'In Progress', 16),
(4, 'House management system', 'Developing a full-stack website for housing company', '0000-00-00', '0000-00-00', 'In Progress', 17),
(5, 'AI template generation', 'creating a website that can generate templates based on AI references', '0000-00-00', '0000-00-00', 'In Progress', 17),
(6, 'testing', 'testing an ecommerce website', '2024-07-04', '2024-07-31', 'Pending', NULL),
(7, 'coding', 'coding a website', '2024-07-04', '2024-07-31', 'Pending', NULL),
(8, 'testing', 'huifkewjnsdc', '2024-08-14', '2024-08-31', 'Pending', 17);

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
  `status` enum('Pending','In Progress','Completed') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tasks`
--

INSERT INTO `tasks` (`task_id`, `project_id`, `task_name`, `description`, `assigned_to`, `start_date`, `end_date`, `status`) VALUES
(1, 1, 'Log In', 'Create front end', NULL, '0000-00-00', '0000-00-00', 'In Progress'),
(6, 4, 'create UI/UX design', 'create all the pages required for the website', 17, '2024-01-01', '2024-10-10', 'In Progress'),
(7, 5, 'google APIs', 'Add google APIs for AI reference', 17, '2024-01-01', '2024-10-10', 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT 'employee',
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
  `emergency_contact_relation` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `first_name`, `last_name`, `email`, `phone`, `address`, `date_joined`, `department`, `job_title`, `date_of_birth`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_relation`) VALUES
(15, 'kanviprojectmanager@gmail.com', '$2y$10$8OfSEDK4B7Wwjiyumj2tI.1aLGkpArAagTh.2hX1UX/5NkoZWtqU2', 'project manager', 'Kanvi', 'Shah', 'kanviprojectmanager@gmail.com', '4375594904', '79 dolomiti court Hamilton', '2024-07-03', 'IT', 'senior manager', '2002-04-19', NULL, NULL, NULL),
(16, 'akhilprojectmanager@gmail.com', '$2y$10$VQHYu1j4oMFupkBs15DKrewOomuXsm8FtAzfvUoxH/6SLEhZ3CiB6', 'project manager', 'Akhil', 'Abraham', 'akhilprojectmanager@gmail.com', '4375594904', '150 Elm ridge drive, Kitchener', '2023-01-03', 'IT', 'senior manager', '2000-10-10', NULL, NULL, NULL),
(17, 'anaiprojectmanager@gmail.com', '$2y$10$znuPu.bv/C2mwMel2Jy36e09t3OVd0u.OvTDLDADB8G73V2LKb3YO', 'project manager', 'Anai', 'Joshy', 'anaiprojectmanager@gmail.com', '4375594904', '43 rainfrew street kitchener', '2022-11-11', 'IT', 'senior manager', '0001-04-20', NULL, NULL, NULL),
(21, 'varunshah@gmail.com', '$2y$10$hUTPnLZr7kt7bdwRHQ1bke3pUKD9ECLl1rrFkwn4gTwOYKTam8Qxu', 'HR', 'Varun', 'Shah', 'varunshah@gmail.com', '4375594904', '34 rainfrew street', '2024-07-09', 'IT', 'HR', '2024-07-01', NULL, NULL, NULL),
(24, 'vilina@gmail.com', '$2y$10$s8Uw4rfkmoxYYZATjiPRouRlAflE/9f2dWFI7Hvb9r3P3ZgtVmmTK', 'employee', 'Vilina', 'Shah', 'vilina@gmail.com', '4375594094', '34 rainfrew street kitchener', '2024-07-02', 'IT', 'employee', '2024-07-04', NULL, NULL, NULL),
(25, 'hanee@gmail.com', '$2y$10$FUQiWysIFtMPKTldn8Uj2uvglL9Q/KvW8S9ERun9ZdQGPQqwV2Msu', 'employee', 'Hanee', 'patel', 'hanee@gmail.com', '2896234306', '34 rainfrew street kitchener', '2024-07-04', 'IT', 'employee', '2024-07-12', NULL, NULL, NULL),
(26, 'charlesalbey007@gmail.com', '$2y$10$rIEnZcDIxzV5L/Xsd7O8AuS5jvOwDQppNA0EYayXnE.MQlhSg9tcS', 'employee', 'Abraham', 'Thotekat Albey', 'charlesalbey007@gmail.com', '2499890218', '34 rainfrew street kitchener', '2024-07-04', 'IT', 'employee', '2024-07-11', NULL, NULL, NULL),
(27, 'ani@gmail.com', '$2y$10$ig/t9yJE8cjkiN367fQSV.O7YRz80fWVodsN.6ux79AOaTU2wlUdu', 'employee', 'anisha', 'patel', 'ani@gmail.com', '2499890218', '34 rainfrew street kitchener', '2024-08-01', 'IT', 'employee', '2024-08-01', NULL, NULL, NULL),
(30, 'divyang@gmail.com', '$2y$10$u1tIvw5toqAurBYvcRiwxuaAAGCZ3xM0/OqlukprDorYIx/mKEZcC', 'employee', 'Divyang', 'Shah', 'divyang@gmail.com', '9104442342', '34 rainfrew street kitchener', '2024-08-01', 'IT', 'developer', '2024-08-01', NULL, NULL, NULL);

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

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
  ADD KEY `approved_by_level1` (`approved_by_level1`),
  ADD KEY `approved_by_level2` (`approved_by_level2`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_details_products`
--
ALTER TABLE `order_details_products`
  ADD PRIMARY KEY (`prod_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`project_id`),
  ADD KEY `manager_id` (`user_id`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `compaint_id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leaverequests`
--
ALTER TABLE `leaverequests`
  MODIFY `leave_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `project_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `projects`
--
ALTER TABLE `projects`
  ADD CONSTRAINT `projects_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
