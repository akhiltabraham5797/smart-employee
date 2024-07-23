<?php
require_once 'db_connection.php';

$sql = "SELECT * FROM complaints cc order by compaint_id desc";

$sql = "SELECT lr.compaint_id, u.first_name, u.last_name, lr.complaint_description, lr.raised_on
        FROM complaints lr
        JOIN Users u ON lr.user_id = u.user_id";

$stmt = $pdo->query($sql);
$leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$message = "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Complaint List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        function setStatus(status, leaveId) {
            document.getElementById('status-' + leaveId).value = status;
        }
    </script>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <nav class="navigation">
        <a class="navigation-logo" href="#">Smart Employee</a>
        <div class="navigation-cont">
            <a class="navigation-link" href="employee-list.php">Home</a>
            <a class="navigation-link" href="complaint-list.php">Complaint List</a>
            <a class="navigation-link" href="management.php">Create Project</a>
            <a class="navigation-link" href="Manage_Leave_Requests.php">Leave Approval</a>
            <a class="navigation-link" href="logout.php">Logout</a>
        </div>
    </nav>
</header>
<main class="container mt-5">
    <h1>Employees Complaint List</h1>
    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>
    <table class="leave-requests-table table-striped">
        <thead>
            <tr>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Complaint Description</th>
                <th>Raised On</th>
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
                        <td width="20%"><?php echo htmlspecialchars($request['first_name']); ?></td>
                        <td width="20%"><?php echo htmlspecialchars($request['last_name']); ?></td>
                        <td width="40%"><?php echo htmlspecialchars($request['complaint_description']); ?></td>
                        <td width="20%"><?php echo htmlspecialchars($request['raised_on']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>

