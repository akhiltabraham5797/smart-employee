<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $leave_id = $_POST['leave_id'];
    $action = $_POST['action'];
    $hr_user_id = $_SESSION["user_id"]; 

    if ($action == 'approve') {
        $status = 'Approved by HR';
        $approval_date = date('Y-m-d');
    } elseif ($action == 'reject') {
        $status = 'Rejected';
        $approval_date = date('Y-m-d');
    }

    $sql = "UPDATE LeaveRequests SET 
            status = :status, 
            approval_date = :approval_date, 
            approved_by = :hr_user_id 
            WHERE leave_id = :leave_id";

    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':approval_date', $approval_date);
    $stmt->bindParam(':hr_user_id', $hr_user_id);
    $stmt->bindParam(':leave_id', $leave_id);

    if ($stmt->execute()) {
        echo "Leave request has been " . strtolower($status);
    } else {
        echo "Error updating record: " . $stmt->errorInfo()[2];
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

$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HR Leave Requests Approval</title>
    <!-- Link to CSS file -->
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
        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row['leave_id']) . "</td>";
                echo "<td>" . htmlspecialchars($row['user_name']) . "</td>";
                echo "<td>" . htmlspecialchars($row['LeaveType']) . "</td>";
                echo "<td>" . htmlspecialchars($row['start_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['end_date']) . "</td>";
                echo "<td>" . htmlspecialchars($row['reason']) . "</td>";
                echo "<td>" . htmlspecialchars($row['requested_on']) . "</td>";
                echo "<td>" . htmlspecialchars($row['pm_name']) . "</td>";
                echo "<td>
                        <form method='post' action=''>
                            <input type='hidden' name='leave_id' value='" . htmlspecialchars($row['leave_id']) . "'>
                            <button type='submit' name='action' value='approve' class='btn btn-success btn-sm mt-2'>Approve</button>
                            <button type='submit' name='action' value='reject' class='btn btn-danger btn-sm mt-2'>Reject</button>
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
$pdo = null;
?>
