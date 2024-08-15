<?php
session_start();
include 'db_connection.php';
// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
}

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

        $salaryDetails = [
            'userId' => $userId,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalHours' => $totalHours,
            'hourlyRate' => $hourlyRate,
            'amount' => $amount,
            'paymentDate' => $paymentDate,
        ];

    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HR View Employee Hours and Salary</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="salary-page">
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
      <div class="navigation-cont">
        <a class="navigation-link" href="employee-dashboard.php">Home</a>
        <a class="navigation-link" href="edit_profile.php">Edit Profile</a>
        <a class="navigation-link" href="Leave_Application.php">Leave Application</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
<main>
<h1>Generate Salary Slip</h1>
    <div class="salary_container">
        <div class="salary_box">
            <form class="salary_generation-form" method="POST" action="">
            <div>
                <label for="start_date">Start Date:</label>
                <input type="date" id="start_date" name="start_date" required>
                <br>
                <label for="end_date">End Date:</label>
                <input type="date" id="end_date" name="end_date" required>
                <br>
                <input type="submit" value="Salary Details">
                <?php
                $profile_data = $pdo->prepare("SELECT * FROM users WHERE user_id=$emp_id");
                $profile_data->execute();
                if ($prof_details = $profile_data->fetch(PDO::FETCH_ASSOC)) {
                ?>
                    <a href="generatepdf.php?id=<?php echo $prof_details['user_id'];?>" class="pay_btn">Generate Payslip</a>
                <?php } ?>
            </div>
            </form>
        </div>
        <div class="salary_display">
        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($error)) {
                echo "<p>$error</p>";
            } else {
                echo "<h2>Salary Details</h2>";
                echo "User ID: {$salaryDetails['userId']}<br>";
                echo "Start Date: {$salaryDetails['startDate']}<br>";
                echo "End Date: {$salaryDetails['endDate']}<br>";
                echo "Total Hours Worked: " . round($salaryDetails['totalHours'], 2) . "<br>";
                echo "Hourly Rate: $" . round($salaryDetails['hourlyRate'], 2) . "<br>"; 
                echo "Amount: $" . round($salaryDetails['amount'], 2) . "<br>";
                echo "Payment Date: {$salaryDetails['paymentDate']}<br>";
            }
        }
        ?>
        </div>
    </div>
</main>
</body>
</html>


