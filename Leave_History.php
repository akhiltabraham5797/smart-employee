<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sems";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = 1;
$sql = "SELECT LeaveType, start_date, end_date, requested_on, status FROM leaverequests WHERE user_id = 1";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave History</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
<header>
  <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="#">Smart Employee</a>
    <div>
      <div class="navbar-nav flex-row">
        <a class="nav-item nav-link active px-2" href="employee-dashboard.php">Home <span class="sr-only">(current)</span></a>
        <a class="nav-item nav-link px-2" href="userProfile.php?id=<?php echo $prof_details['user_id'];?>">Edit Profile</a>
        <a class="nav-item nav-link px-2" href="Leave_Application.php?id=<?php echo $prof_details['user_id'];?>">Leave Application</a>
        <a class="nav-item nav-link px-2" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
<main class="leavebody">
    <div class="leavecontainer leave-history-container">
        <h1>Leave History</h1>
        <table>
            <thead>
                <tr>
                    <th>Leave Type</th>
                    <th>From Date</th>
                    <th>To Date</th>
                    <th>Requested Date</th>
                    <th>Status</th>
                    <!-- <th>Action</th> -->
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["LeaveType"] . "</td>";
                        echo "<td>" . $row["start_date"] . "</td>";
                        echo "<td>" . $row["end_date"] . "</td>";
                        echo "<td>" . $row["requested_on"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        // echo '<td><button class="view-details">View Details</button></td>';
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='6'>No leave history found</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
    </div>
</main>
</body>
</html>
