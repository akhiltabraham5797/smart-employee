# Smart Employement Management System (SEMS)

CREATE DATABASE IF NOT EXISTS sems;

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(50),
    first_name VARCHAR(100),
    last_name VARCHAR(100),
    email VARCHAR(255) UNIQUE NOT NULL,
    phone VARCHAR(15),
    address VARCHAR(255),
    date_joined DATE,
    department VARCHAR(100),
    job_title VARCHAR(100),
    date_of_birth DATE,
    emergency_contact_name VARCHAR(100),
    emergency_contact_phone VARCHAR(15),
    emergency_contact_relation VARCHAR(100)
);


CREATE TABLE Projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    project_name VARCHAR(100) NOT NULL,
    description TEXT,
    start_date DATE,
    end_date DATE,
    status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    manager_id INT,
    FOREIGN KEY (manager_id) REFERENCES Users(user_id) ON DELETE SET NULL
);

CREATE TABLE Tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    task_name VARCHAR(100) NOT NULL,
    description TEXT,
    assigned_to INT,
    start_date DATE,
    end_date DATE,
    status ENUM('Pending', 'In Progress', 'Completed') DEFAULT 'Pending',
    FOREIGN KEY (project_id) REFERENCES Projects(project_id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_to) REFERENCES Users(user_id) ON DELETE SET NULL
);

CREATE TABLE Attendance (
    attendance_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    date DATE NOT NULL,
    clock_in_time TIME,
    clock_out_time TIME,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);


CREATE TABLE Salaries (
    salary_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    pay_period_start DATE,
    pay_period_end DATE,
    project_name VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE
);

CREATE TABLE LeaveRequests (
    leave_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    requested_on DATE NOT NULL,
    approved_by_level1 INT,
    level1_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    level1_approved_on DATE,
    approved_by_level2 INT,
    level2_status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    level2_approved_on DATE,
    FOREIGN KEY (user_id) REFERENCES Users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (approved_by_level1) REFERENCES Users(user_id) ON DELETE SET NULL,
    FOREIGN KEY (approved_by_level2) REFERENCES Users(user_id) ON DELETE SET NULL
);

