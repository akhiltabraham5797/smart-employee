<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

function calculateTotalHours($pdo, $userId, $startDate, $endDate) {
    $stmt = $pdo->prepare("SELECT clock_in_time, clock_out_time FROM attendance WHERE user_id = ? AND date BETWEEN ? AND ?");
    $stmt->execute([$userId, $startDate, $endDate]);
    $totalHours = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $clockIn = new DateTime($row['clock_in_time']);
        $clockOut = new DateTime($row['clock_out_time']);
        $interval = $clockIn->diff($clockOut);
        $hours = $interval->h + ($interval->i / 60);
        $totalHours += round($hours, 2);
    }
    return $totalHours;
}

function getHourlyRate($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT hourly_rate FROM users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new Exception("User not found.");
    }
    return $user['hourly_rate'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $startDate = $_POST["start_date"];
    $endDate = $_POST["end_date"];
    $userId = $_SESSION["user_id"];

    try {
        $totalHours = calculateTotalHours($pdo, $userId, $startDate, $endDate);


        $hourlyRate = getHourlyRate($pdo, $userId);
        $amount = round($hourlyRate * $totalHours, 2); 
        $stmt = $pdo->prepare("INSERT INTO salaries (user_id, amount, payment_date, pay_period_start, pay_period_end, hourly_rate, total_hours) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $paymentDate = date('Y-m-d');
        $stmt->execute([$userId, $amount, $paymentDate, $startDate, $endDate, $hourlyRate, $totalHours]);

        echo "<h2>Salary Slip</h2>";
        echo "User ID: $userId<br>";
        echo "Start Date: $startDate<br>";
        echo "End Date: $endDate<br>";
        echo "Total Hours Worked: " . round($totalHours, 2) . "<br>";
        echo "Hourly Rate: $" . round($hourlyRate, 2) . "<br>"; 
        echo "Amount: $" . round($amount, 2) . "<br>";
        // echo "Payment Date: $paymentDate<br>";

    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Generate Salary Slip</title>
</head>
<body>
    <h2>Generate Salary Slip</h2>
    <form method="POST" action="">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>
        <br>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>
        <br>
        <input type="submit" value="Generate">
    </form>
</body>
</html>
