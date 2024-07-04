<?php
// Database connection
require_once ('db_connection.php');
session_start();
// Check if the user is logged in
if (isset($_GET['project_id'])) {
  $project_id = $_GET['project_id'];
}
// $project_id = "1";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Smart Employee | Project Tracking</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- link for css file -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<!-- Code to retrieve project details -->
<?php
  $project_data = $pdo->prepare("SELECT p.project_id as project_id, p.project_name as project_name, p.description as project_description, p.status as project_status, p.end_date as deadline,
           t.task_id as task_id, t.task_name as task_name, t.status as task_status, t.assigned_to as assigned_to, t.project_id as project_id
    FROM projects p
    LEFT JOIN tasks t ON p.project_id = t.project_id
    WHERE p.project_id = $project_id");
  $project_data->execute();
  if ($project_details = $project_data->fetch(PDO::FETCH_ASSOC)) {

  
  ?>
<body class="pro_track">
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
<main>
  <h1 class="emp_head">Project Tracking</h1>
  <div class="pro_box">
    <div class="pro_wrap">
      <p class="pro_label">Project ID:</p>
      <p class="pro_value"><?php echo $project_details['project_id']; ?></p>
    </div>
    <div class="pro_wrap">
      <p class="pro_label">Project Name:</p>
      <p class="pro_value"><?php echo $project_details['project_name']; ?></p>
    </div>
    <div class="pro_wrap">
      <p class="pro_label">Project Description:</p>
      <p class="pro_value"><?php echo $project_details['project_description']; ?></p>
    </div>
    <div class="pro_wrap">
      <p class="pro_label">Deadline:</p>
      <p class="pro_value"><?php echo $project_details['deadline']; ?></p>
    </div>
    <div class="pro_task_outer">
      <p class="pro_label">Tasks</p>
      <div class="pro_value">
        <div class="pro_task_box">
          <!-- Code to retrieve task details of the project -->
          <?php 
          $task_data = $pdo->prepare("SELECT * FROM tasks WHERE project_id=$project_id");
          $task_data->execute();
          $tasks=[];
            while ($task_details = $task_data->fetch(PDO::FETCH_ASSOC)) {
              $tasks[] = $task_details;
            }
          if(!empty($tasks)){
          foreach ($tasks as $task) : 
          ?>
            <div class="pro_task_wrap">
              <div class="pro_task_row1">
                <p class="pro_task_name"><?php echo $task['task_name']; ?></p>
                <p class="pro_task_end">Deadline: <span><?php echo $task['end_date']; ?></span></p>
              </div>
              <p class="pro_task_status">Status: <span><?php echo $task['status']; ?></span></p>
                  <?php 
                  $task_id = $task['task_id'];
                  $assignee_data = $pdo->prepare("SELECT t.task_id as task_id, u.first_name as first_name, u.last_name as last_name 
                  FROM tasks t JOIN users u ON t.assigned_to = u.user_id WHERE task_id=$task_id");
                  $assignee_data->execute();
                  while ($assignee_details = $assignee_data->fetch(PDO::FETCH_ASSOC)) {              
                  ?>
                    <p class="pro_task_assignee">Assigned To: <span><?php echo $assignee_details['first_name'];?></span> <span><?php echo $assignee_details['last_name']; ?></span></p>
                  <?php } ?>
            </div>
            <?php endforeach; 
            }
            else{
            echo "<p>No tasks found for this project</p>";
          }
            ?>
          </div>
      </div>
    </div>
    <!-- Code to retrieve count of completed tasks and calculate the percentage of work done -->
    <?php 
          $completed_tasks = $pdo->prepare("SELECT status FROM tasks WHERE project_id = $project_id AND status = 'Completed'");
          $completed_tasks->execute();
          $status=[];
            while ($completed_tasks_details = $completed_tasks->fetch(PDO::FETCH_ASSOC)) {
              $completed_status[] = $completed_tasks_details;
            }
            if(!empty($completed_status)){
              $completed_count = count($completed_status); 
            }
    ?>
    <?php 
          $total_tasks = $pdo->prepare("SELECT status FROM tasks WHERE project_id = $project_id");
          $total_tasks->execute();
          $status=[];
            while ($total_tasks_details = $total_tasks->fetch(PDO::FETCH_ASSOC)) {
              $total_status[] = $total_tasks_details;
            }
            if(!empty($completed_status)){
              $total_count = count($total_status); 
            }
    ?>
    <?php
      if(!empty($completed_count) && !empty($total_count)){
      $progress_percentage = ($completed_count/$total_count)*100;
    }
    ?>
 <div class="pro_wrap">
      <p class="pro_label">Project Status:</p>
      <!-- Displaying project status in a progress bar -->
      <div class="pro_value">
        <div class="pro_progress_box">
        <?php
        if (!empty($progress_percentage)) {
        ?>
          <div class="pro_progress_cont" <?php echo "style='width: $progress_percentage%'"; ?>></div>
        <?php } ?>
        </div>
        <?php
        if (!empty($progress_percentage)) {
        ?>
        <div>This project is <?php echo $progress_percentage; ?>% completed.</div>
        <?php } ?>
      </div>
    </div>
  </div>
</main>
</body>
<?php
}
?>
</html>
