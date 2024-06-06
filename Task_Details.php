<?php
// Database connection settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sems";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$task_id = 8; // Assuming we are fetching details for task with ID 1

// Fetch task details from the database
$sql = "SELECT tasks.task_name, projects.project_name, tasks.description, tasks.start_date, tasks.end_date, tasks.status, users.first_name as project_manager 
        FROM tasks 
        JOIN projects ON tasks.project_id = projects.project_id 
        JOIN users ON projects.user_id = users.user_id 
        WHERE tasks.task_id = $task_id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch the task details
    $task = $result->fetch_assoc();
} else {
    echo "No task found with the specified ID.";
    $conn->close();
    exit;
}

// Handle form submission for updating task status
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];

    // Update task status in the database
    $update_sql = "UPDATE tasks SET status='$status' WHERE id=$task_id";

    if ($conn->query($update_sql) === TRUE) {
        echo "<script>alert('Task status updated successfully');</script>";
    } else {
        echo "<script>alert('Error updating task status: " . $conn->error . "');</script>";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Details</title>
    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body>
    <div class="container task-details-container">
        <h1>Task Details</h1>
        <form id="task-details-form" method="post" action="Task_Details.php">
            <div class="task-info">
                <label for="task-name">Task Name:</label>
                <p id="task-name"><?php echo $task['task_name']; ?></p>
                
                <label for="project-manager">Project Manager:</label>
                <p id="project-manager"><?php echo $task['project_manager']; ?></p>
                
                <label for="project-name">Project Name:</label>
                <p id="project-name"><?php echo $task['project_name']; ?></p>
                
                <label for="task-for-the-day">Task for the Day:</label>
                <p id="task-for-the-day"><?php echo $task['description']; ?></p>
                
                <label for="backlog-tasks">Backlog Tasks:</label>
                <p id="backlog-tasks">Update logo design, Create footer design</p>
                
                <label for="to-be-completed">To Be Completed Tasks:</label>
                <p id="to-be-completed">Finalize home page design, Start about us page</p>
                
                <label for="deadline">Deadline:</label>
                <p id="deadline"><?php echo $task['end_date']; ?></p>
                
                <label for="notes">Notes:</label>
                <p id="notes">Ensure the design is mobile-responsive</p>

                <label for="status">Task Status:</label>
                <select id="status" name="status" required>
                    <option value="completed" <?php if ($task['status'] == 'completed') echo 'selected'; ?>>Completed</option>
                    <option value="in_progress" <?php if ($task['status'] == 'in_progress') echo 'selected'; ?>>In Progress</option>
                    <option value="backlogged" <?php if ($task['status'] == 'backlogged') echo 'selected'; ?>>Not Completed</option>
                </select>

                <div class="task-info-buttons">
                    <button type="submit">Update</button>
                    <button type="button" onclick="window.location.href='dashboard.php';">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</body>
</html>