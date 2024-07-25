<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sems";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_id = $_POST['leave_id'];
    $action = $_POST['action'];
    $hr_user_id = 12; 

    if ($action == 'approve') {
        $status = 'Approved by HR';
        $approval_date = date('Y-m-d');
        $approved_by = $hr_user_id;
    } elseif ($action == 'reject') {
        $status = 'Rejected';
        $approval_date = date('Y-m-d'); 
        $approved_by = $hr_user_id;
    }

    $sql = "UPDATE LeaveRequests SET 
            status = '$status', 
            approval_date = '$approval_date', 
            approved_by = '$approved_by' 
            WHERE leave_id = $leave_id";

    if ($conn->query($sql) === TRUE) {
        echo "Leave request has been " . strtolower($status);
    } else {
        echo "Error updating record: " . $conn->error;
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$sql = "SELECT lr.leave_id, lr.user_id, u.first_name AS user_name, lr.LeaveType, lr.start_date, lr.end_date, lr.reason, lr.requested_on, 
        pm.first_name AS pm_name
        FROM LeaveRequests lr
        JOIN Users u ON lr.user_id = u.user_id
        JOIN Users pm ON lr.approved_by = pm.user_id
        WHERE lr.status = 'Approved by PM'";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- link for css file -->
  <link rel="stylesheet" href="assets/css/style.css">
    <title>HR Leave Requests Approval</title>
</head>
<body>
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
    <div class="navigation-cont">
        <a class="navigation-link" href="hr-dashboard.php">Home</a>
        <a class="navigation-link" href="HR_Manage_Leave_Request.php">Leave Approval</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
    <main class="hr_leave">
        <h1 style="text-align: center;">Leave Approval</h1>
        <table border="1" class="hr_leave_table leave-requests-table table-striped">
            <tr>
                <th>Leave ID</th>
                <th>User Name</th>
                <th>Leave Type</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Requested On</th>
                <th>Approved By PM</th>
                <th>Action</th>
            </tr>
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row['leave_id'] . "</td>";
                    echo "<td>" . $row['user_name'] . "</td>";
                    echo "<td>" . $row['LeaveType'] . "</td>";
                    echo "<td>" . $row['start_date'] . "</td>";
                    echo "<td>" . $row['end_date'] . "</td>";
                    echo "<td>" . $row['reason'] . "</td>";
                    echo "<td>" . $row['requested_on'] . "</td>";
                    echo "<td>" . $row['pm_name'] . "</td>";
                    echo "<td>
                            <form method='post' action=''>
                                <input type='hidden' name='leave_id' value='" . $row['leave_id'] . "'>
                                <button type='submit' name='action' value='approve' class='btn btn-success btn-sm mt-2'>Approve</button>
                                <button type='submit' name='action' value='reject' class='sbtn btn-danger btn-sm mt-2'>Reject</button>
                            </form>
                        </td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>No leave requests found</td></tr>";
            }
            ?>
        </table>
    </main>
</body>
</html>

<?php
$conn->close();
?>
