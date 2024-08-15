<?php
session_start();
include 'db_connection.php';

// Retrieve the logged-in user's ID from the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    echo "User is not logged in.";
    exit;
}

// Query to get the task details for the logged-in user
$sql = "SELECT tasks.task_id, tasks.task_name, projects.project_name, tasks.description, tasks.start_date, tasks.end_date, tasks.status, users.first_name as project_manager 
        FROM tasks 
        JOIN projects ON tasks.project_id = projects.project_id 
        JOIN users ON projects.user_id = users.user_id 
        WHERE tasks.assigned_to = :user_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "No tasks found for the logged-in user.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $task_id = $task['task_id'];  // Ensure this gets the correct task ID

    $update_sql = "UPDATE tasks SET status=:status WHERE task_id=:task_id AND assigned_to=:user_id";
    
    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $update_stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
    $update_stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        // Check if any row was actually updated
        if ($update_stmt->rowCount() > 0) {
            echo "<script>alert('Task status updated successfully');</script>";
        } else {
            echo "<script>alert('Task status not updated. No matching records found.');</script>";
        }
    } else {
        $errorInfo = $update_stmt->errorInfo();
        echo "<script>alert('Error updating task status: " . $errorInfo[2] . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
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
    <div class="leavecontainer task-details-container">
        <h1>Task Details</h1>
        <form id="task-details-form" method="post" action="">
            <div class="task-info">
                <label for="task-name">Task Name:</label>
                <p id="task-name"><?php echo htmlspecialchars($task['task_name']); ?></p>
                
                <label for="project-manager">Project Manager:</label>
                <p id="project-manager"><?php echo htmlspecialchars($task['project_manager']); ?></p>
                
                <label for="project-name">Project Name:</label>
                <p id="project-name"><?php echo htmlspecialchars($task['project_name']); ?></p>
                
                
                <label for="deadline">Deadline:</label>
                <p id="deadline"><?php echo htmlspecialchars($task['end_date']); ?></p>
                
                <label for="notes">Notes:</label>
                <p id="notes"><?php echo htmlspecialchars($task['description']); ?></p>

                <label for="status">Task Status:</label>
                <select id="status" name="status" required>
                    <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="In Progress" <?php if ($task['status'] == 'Pending') echo 'selected'; ?>>In Progress</option>
                    <option value="Not Completed" <?php if ($task['status'] == 'Not Completed') echo 'selected'; ?>>Not Completed</option>
                </select>

                <div class="task-info-buttons">
                    <button type="submit">Update</button>
                    <button type="button" onclick="window.location.href='employee-dashboard.php';">Cancel</button>
                </div>
            </div>

        </form>
    </div>
</main>
</body>
</html>
