<?php

include 'db_connection.php';

$task_id = 1; 

$sql = "SELECT tasks.task_name, projects.project_name, tasks.description, tasks.start_date, tasks.end_date, tasks.status, users.first_name as project_manager 
        FROM tasks 
        JOIN projects ON tasks.project_id = projects.project_id 
        JOIN users ON projects.user_id = users.user_id 
        WHERE tasks.task_id = :task_id";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    $task = $stmt->fetch(PDO::FETCH_ASSOC);
} else {
    echo "No task found with the specified ID.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $update_sql = "UPDATE tasks SET status=:status WHERE task_id=:task_id";

    $update_stmt = $pdo->prepare($update_sql);
    $update_stmt->bindParam(':status', $status, PDO::PARAM_STR);
    $update_stmt->bindParam(':task_id', $task_id, PDO::PARAM_INT);

    if ($update_stmt->execute()) {
        echo "<script>alert('Task status updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating task status: " . $update_stmt->errorInfo()[2] . "');</script>";
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
  <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="#">Smart Employee</a>
    <div>
      <div class="navbar-nav flex-row">
        <a class="nav-item nav-link active px-2" href="employee-dashboard.php">Home <span class="sr-only">(current)</span></a>
        <a class="nav-item nav-link px-2" href="userProfile.php?id=<?php echo $prof_details['user_id']; ?>">Edit Profile</a>
        <a class="nav-item nav-link px-2" href="Leave_Application.php?id=<?php echo $prof_details['user_id']; ?>">Leave Application</a>
        <a class="nav-item nav-link px-2" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
<main class="leavebody">
    <div class="leavecontainer task-details-container">
        <h1>Task Details</h1>
        <form id="task-details-form" method="post" action="Task_Details.php">
            <div class="task-info">
                <label for="task-name">Task Name:</label>
                <p id="task-name"><?php echo htmlspecialchars($task['task_name']); ?></p>
                
                <label for="project-manager">Project Manager:</label>
                <p id="project-manager"><?php echo htmlspecialchars($task['project_manager']); ?></p>
                
                <label for="project-name">Project Name:</label>
                <p id="project-name"><?php echo htmlspecialchars($task['project_name']); ?></p>
                
                <label for="task-for-the-day">Task for the Day:</label>
                <p id="task-for-the-day"><?php echo htmlspecialchars($task['description']); ?></p>
                
                <label for="backlog-tasks">Backlog Tasks:</label>
                <p id="backlog-tasks">Update logo design, Create footer design</p>
                
                <label for="to-be-completed">To Be Completed Tasks:</label>
                <p id="to-be-completed">Finalize home page design, Start about us page</p>
                
                <label for="deadline">Deadline:</label>
                <p id="deadline"><?php echo htmlspecialchars($task['end_date']); ?></p>
                
                <label for="notes">Notes:</label>
                <p id="notes">Ensure the design is mobile-responsive</p>

                <label for="status">Task Status:</label>
                <select id="status" name="status" required>
                    <option value="Completed" <?php if ($task['status'] == 'Completed') echo 'selected'; ?>>Completed</option>
                    <option value="In Progress" <?php if ($task['status'] == 'In Progress') echo 'selected'; ?>>In Progress</option>
                    <option value="Not Completed" <?php if ($task['status'] == 'Not Completed') echo 'selected'; ?>>Not Completed</option>
                </select>

                <div class="task-info-buttons">
                    <button type="submit">Update</button>
                    <button type="button" onclick="window.location.href='employee-dashboard.php';">Cancel</button>
                </div>
            </div>
            <input type="hidden" name="task_id" value="<?php echo htmlspecialchars($task_id); ?>">
        </form>
    </div>
</main>
</body>
</html>
