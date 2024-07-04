<?php
require_once 'db_connection.php';

$sql = "SELECT lr.leave_id, u.first_name, u.last_name, lr.start_date, lr.end_date, lr.reason, lr.status 
        FROM LeaveRequests lr
        JOIN Users u ON lr.user_id = u.user_id
        WHERE lr.status = 'Pending'";

$stmt = $pdo->query($sql);
$leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    $update_sql = "UPDATE LeaveRequests SET status = :status, description = :description WHERE leave_id = :leave_id";
    $stmt = $pdo->prepare($update_sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':leave_id', $leave_id);

    if ($stmt->execute()) {
        $message = "Leave request updated successfully";
        header("Refresh:0");
    } else {
        $message = "Error updating leave request: " . $stmt->errorInfo()[2];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Leave Requests</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        function setStatus(status, leaveId) {
            document.getElementById('status-' + leaveId).value = status;
        }
    </script>
</head>
<body>
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
      <div class="navigation-cont">
        <a class="navigation-link" href="projectmanager.php">Home</a>
        <a class="navigation-link" href="management.php">Create Project</a>
        <a class="navigation-link" href="Manage_Leave_Requests.php">Leave Approval</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
<main class="container mt-5">
    <h1>Manage Leave Requests</h1>
    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Employee Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($leave_requests)): ?>
                <tr>
                    <td colspan="6" class="text-center">No leave requests found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($leave_requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['first_name'] . ' ' . $request['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($request['reason']); ?></td>
                        <td><?php echo htmlspecialchars($request['status']); ?></td>
                        <td>
                            <form method="post" style="display:inline-block;">
                                <input type="hidden" name="leave_id" value="<?php echo $request['leave_id']; ?>">
                                <input type="hidden" name="status" id="status-<?php echo $request['leave_id']; ?>" value="">
                                <div class="form-group">
                                    <textarea name="description" rows="2" cols="30" placeholder="Description" required class="form-control mb-2"></textarea>
                                </div>
                                <button type="submit" class="btn btn-success btn-sm mt-2" onclick="setStatus('Approved', <?php echo $request['leave_id']; ?>)">Approve</button>
                                <button type="submit" class="btn btn-danger btn-sm mt-2" onclick="setStatus('Rejected', <?php echo $request['leave_id']; ?>)">Reject</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
