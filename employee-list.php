<?php
require_once 'db_connection.php';

$sql = "SELECT * FROM users ORDER BY first_name";
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
    <title>Manage Employees</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        function deleteEmployee(leaveId) 
        {
            if(confirm("Are you sure you want to delete employee?"))
            {
                // Add deletion logic here
            }   
        }
    </script>
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
<main class="container mt-5">
    <h1>Manage Employees</h1>
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
                <th>Email ID</th>
                <th>Job Title</th>
                <th>Address</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($leave_requests)): ?>
                <tr>
                    <td colspan="6" class="text-center">No employees found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($leave_requests as $request): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($request['first_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($request['email']); ?></td>
                        <td><?php echo htmlspecialchars($request['role']); ?></td>
                        <td><?php echo htmlspecialchars($request['address']); ?></td>
                        <td>
                            <a href="edit_profile.php?eid=<?php echo $request['user_id']; ?>&redirect=employee-list">
                                <button type="button" class="btn btn-success btn-sm mt-2">Edit</button>
                            </a>
                            <!-- <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteEmployee('<?php echo $request['user_id']; ?>')">Delete</button> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>
</body>
</html>
