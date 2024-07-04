# Smart Employement Management System (SEMS)

CREATE DATABASE IF NOT EXISTS sems;

USE sems;

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
    user_id INT,
    FOREIGN KEY (manager_id) REFERENCES Users(user_id) ON DELETE SET NULL
);

ALTER TABLE users
ADD COLUMN project_id INT,
ADD CONSTRAINT fk_project_id
    FOREIGN KEY (project_id) REFERENCES projects(project_id);

CREATE TABLE Tasks (
    task_id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT,
    task_name VARCHAR(100) NOT NULL,
    description TEXT,
    assigned_to INT,
    start_date DATE,
    end_date DATE,
    status ENUM('Pending', 'In Progress', 'Completed', 'Not Completed') DEFAULT 'Pending',
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

CREATE TABLE IF NOT EXISTS LeaveRequests (
    leave_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    LeaveType ENUM('casual', 'sick', 'earned') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    reason TEXT NOT NULL,
    status ENUM('Pending', 'Approved', 'Rejected') NOT NULL DEFAULT 'Pending',
    requested_on DATE NOT NULL,
    description TEXT,
    FOREIGN KEY (user_id) REFERENCES Users(user_id)
);



-- INSERT INTO Users (username, password, role, first_name, last_name, email, phone, address, date_joined, department, job_title, date_of_birth, emergency_contact_name, emergency_contact_phone, emergency_contact_relation)
-- VALUES ('jdoe', 'password123', 'employee', 'John', 'Doe', 'john.doe@example.com', '555-1234', '123 Elm Street', '2020-01-15', 'Development', 'Developer', '1990-05-15', 'Jane Doe', '555-5678', 'Spouse');

-- INSERT INTO Projects (project_name, description, start_date, end_date, status, user_id)
-- VALUES ('Website Redesign', 'Redesign the company website for better UX', '2023-06-01', '2023-12-01', 'In Progress', 1);

-- INSERT INTO Tasks (project_id, task_name, description, assigned_to, start_date, end_date, status)
-- VALUES (1, 'Update Logo', 'Update the company logo with the new design', 1, '2023-06-15', '2023-06-30', 'In Progress'),
--        (1, 'Create Footer Design', 'Design the footer for the new website layout', 1, '2023-07-01', '2023-07-15', 'Pending');
