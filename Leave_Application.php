<?php
session_start();
require_once('db_connection.php'); 

$message = "";

if (isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $emp_id;
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];
    $requested_on = date("Y-m-d");

    $sql = "INSERT INTO leaverequests (user_id, LeaveType, start_date, end_date, reason, status, requested_on)
            VALUES (:user_id, :leave_type, :start_date, :end_date, :description, 'Pending', :requested_on)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':leave_type', $leave_type);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':requested_on', $requested_on);
        $stmt->execute();
        $message = "New leave request submitted successfully";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }

    $pdo = null; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var message = "<?php echo $message; ?>";
            if (message) {
                alert(message);
            }
        });
    </script>
</head>
<body class="index-page">
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
<main class="leavebody">
    <div class="leavecontainer leave-application-container">
        <h1>Apply for Leave</h1>
        <form id="leave-form" method="post" action="Leave_Application.php">
            <input type="hidden" name="user_id" value="<?php echo $emp_id; ?>"> 
            <label for="leave-type">Type of Leave:</label>
            <select id="leave-type" name="leave_type" required>
                <option value="casual">Casual</option>
                <option value="sick">Sick</option>
                <option value="earned">Earned</option>
            </select>

            <label for="start-date">From Date:</label>
            <input type="date" id="start-date" name="start_date" required>

            <label for="end-date">To Date:</label>
            <input type="date" id="end-date" name="end_date" required>

            <label for="description">Description:</label>
            <textarea id="description" name="description" rows="4" placeholder="Brief description about the leave" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
</main>
</body>
</html>
