<?php

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sems";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$task_id = 1; 
$sql = "SELECT tasks.task_name, projects.project_name, tasks.description, tasks.start_date, tasks.end_date, tasks.status, users.first_name as project_manager 
        FROM tasks 
        JOIN projects ON tasks.project_id = projects.project_id 
        JOIN users ON projects.user_id = users.user_id 
        WHERE tasks.task_id = $task_id";

$result = $conn->query($sql);

if ($result->num_rows!= false) {
    
    $task = $result->fetch_assoc();
} else {
    echo "No task found with the specified ID.";
    $conn->close();
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <link rel="stylesheet" href="assets/css/styles.css">
</head>
<body class="leavebody">
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
    <div class="leavecontainer task-details-container">
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
