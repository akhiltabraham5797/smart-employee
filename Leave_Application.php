<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sems";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = 1;
    $leave_type = $_POST['leave_type'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $description = $_POST['description'];
    $requested_on = date("Y-m-d");

    $sql = "INSERT INTO leaverequests (user_id, LeaveType, start_date, end_date, reason, status, requested_on)
            VALUES ('$user_id', '$leave_type', '$start_date', '$end_date', '$description', 'Pending', '$requested_on')";

    if ($conn->query($sql) === TRUE) {
        $message = "New leave request submitted successfully";
    } else {
        $message = "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var message = "<?php echo $message; ?>";
            if (message) {
                alert(message);
            }
        });
    </script>
</head>

<body class="leavebody">
    
    <div class="leavecontainer leave-application-container">
        <h1>Apply for Leave</h1>
        <form id="leave-form" method="post" action="Leave_Application.php">
            <input type="hidden" name="user_id" value="1"> 
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
</body>
</html>
