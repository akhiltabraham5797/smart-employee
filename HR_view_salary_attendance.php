<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

function fetchEmployees($pdo) {
    $stmt = $pdo->query("SELECT user_id, first_name, last_name FROM Users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function calculateTotalHours($pdo, $userId, $startDate, $endDate) {
    $stmt = $pdo->prepare("SELECT clock_in_time, clock_out_time FROM attendance WHERE user_id = ? AND date BETWEEN ? AND ?");
    $stmt->execute([$userId, $startDate, $endDate]);
    $totalHours = 0;

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $clockIn = new DateTime($row['clock_in_time']);
        $clockOut = new DateTime($row['clock_out_time']);
        $interval = $clockIn->diff($clockOut);
        $hours = $interval->h + ($interval->i / 60); // Convert minutes to hours
        $totalHours += $hours;
    }
    return round($totalHours, 2);
}

function getHourlyRate($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT hourly_rate FROM Users WHERE user_id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$user) {
        throw new Exception("User not found.");
    }
    return $user['hourly_rate'];
}

$employees = fetchEmployees($pdo);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HR View Employee Hours and Salary</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <nav class="navigation">
        <a class="navigation-logo" href="#">Smart Employee</a>
        <div class="navigation-cont">
            <a class="navigation-link" href="hr-dashboard.php">Home</a>
            <a class="navigation-link" href="HR_Manage_Leave_Request.php">Leave Approval</a>
            <a class="navigation-link" href="logout.php">Logout</a>
        </div>
    </nav>
</header>
<main>
    <h1 style="text-align:center;">View Employee Hours and Salary</h1>
    <div class="salary_container">
        <div class="salary_box">
            <form class="salary_view-form" method="POST" action="">
                <div class="field_wrap">
                    <label for="user_id">Select Employee:</label>
                    <select id="user_id" name="user_id" required>
                        <?php foreach ($employees as $employee): ?>
                            <option value="<?= $employee['user_id'] ?>"><?= $employee['first_name'] . ' ' . $employee['last_name'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div> 
                <div class="field_wrap">
                    <label for="start_date">Start Date:</label>
                    <input type="date" id="start_date" name="start_date" required>
                </div> 
                <div class="field_wrap">
                    <label for="end_date">End Date:</label>
                    <input type="date" id="end_date" name="end_date" required>
                </div> 
                    <input type="submit" value="View Details">
                
            </form>
        </div>
        <div class="salary_display">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $userId = $_POST["user_id"];
            $startDate = $_POST["start_date"];
            $endDate = $_POST["end_date"];

            try {
                $totalHours = calculateTotalHours($pdo, $userId, $startDate, $endDate);
                $hourlyRate = getHourlyRate($pdo, $userId);
                $amount = round($hourlyRate * $totalHours, 2);

                echo "<h2>Employee Work and Salary Details</h2>";
                echo "User ID: $userId<br>";
                echo "Start Date: $startDate<br>";
                echo "End Date: $endDate<br>";
                echo "Total Hours Worked: $totalHours<br>";
                echo "Hourly Rate: $$hourlyRate<br>";
                echo "Amount: $$amount<br>";

            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }
        ?>
        </div>
    </div>
</main>
</body>
</html>

