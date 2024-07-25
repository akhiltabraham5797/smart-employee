<?php
session_start();
include 'db_connection.php';

function logWorkHours($userId, $action, $pdo) {
    $timestamp = date('Y-m-d H:i:s');
    $date = date('Y-m-d');
    $time = date('H:i:s');

    if ($action === 'login') {
        // Insert login time
        $stmt = $pdo->prepare("INSERT INTO attendance (user_id, date, clock_in_time) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $date, $time]);
    } else if ($action === 'logout') {
        // Update with logout time
        $stmt = $pdo->prepare("UPDATE attendance SET clock_out_time = ? WHERE user_id = ? AND date = ? AND clock_out_time IS NULL");
        $stmt->execute([$time, $userId, $date]);

        // Calculate and update work hours
        $stmt = $pdo->prepare("SELECT clock_in_time, clock_out_time FROM attendance WHERE user_id = ? AND date = ?");
        $stmt->execute([$userId, $date]);
        $attendance = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($attendance) {
            $clockInTime = new DateTime($attendance['clock_in_time']);
            $clockOutTime = new DateTime($attendance['clock_out_time']);
            
            // Calculate the difference in seconds
            $interval = $clockInTime->diff($clockOutTime);
            $hoursWorked = $interval->h + ($interval->i / 60) + ($interval->s / 3600);

            // Update the work hours in the database
            $updateStmt = $pdo->prepare("UPDATE attendance SET work_hours = ? WHERE user_id = ? AND date = ?");
            $updateStmt->execute([$hoursWorked, $userId, $date]);
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $emailErr = $passwordErr = "";

    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $pdo->prepare("SELECT user_id, first_name, last_name, phone, password, role, address, date_joined, department, job_title, date_of_birth FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $hashed_password = $result["password"];

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $result["user_id"];
                $_SESSION["firstname"] = $result["first_name"];
                $_SESSION["lastname"] = $result["last_name"];
                $_SESSION["phone"] = $result["phone"];
                $_SESSION["role"] = $result["role"];
                $_SESSION["address"] = $result["address"];
                $_SESSION["date_joined"] = $result["date_joined"];
                $_SESSION["department"] = $result["department"];
                $_SESSION["job_title"] = $result["job_title"];
                $_SESSION["date_of_birth"] = $result["date_of_birth"];

                // Log work hours on login
                logWorkHours($result["user_id"], 'login', $pdo);

                // Redirect based on user role
                switch ($result["role"]) {
                    case 'HR':
                        header("Location: hr-dashboard.php");
                        break;
                    case 'project manager':
                        header("Location: projectmanager.php");
                        break;
                    case 'employee':
                        header("Location: employee-dashboard.php");
                        break;
                    default:
                        echo "<script>console.error('Unknown role.');</script>";
                }
                exit();
            } else {
                echo "<script>alert('Invalid email or password.');window.location.href = 'login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.');window.location.href = 'login.php';</script>";
            exit();
        }
    }
}

$pdo = null;
?>
