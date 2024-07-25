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

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    // Log work hours on logout
    logWorkHours($userId, 'logout', $pdo);
    unset($_SESSION['user_id']);
}

header("Location: login.php");
die;
?>
