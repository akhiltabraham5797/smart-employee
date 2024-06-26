<?php
// Database connection
require_once ('db_connection.php');
session_start();
// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Smart Employee | Employee Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

  <!-- link for css file -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<?php
  $profile_data = $pdo->prepare("SELECT * FROM users WHERE user_id=$emp_id");
  $profile_data->execute();
  if ($prof_details = $profile_data->fetch(PDO::FETCH_ASSOC)) {
  ?>
<body class="index-page emp_dashboard">
<header>
  <nav class="navbar navbar-light bg-light">
    <a class="navbar-brand" href="#">Smart Employee</a>
    <div>
      <div class="navbar-nav flex-row">
        <a class="nav-item nav-link active px-2" href="employee-dashboard.php">Home <span class="sr-only">(current)</span></a>
        <a class="nav-item nav-link px-2" href="userProfile.php">Edit Profile</a>
        <a class="nav-item nav-link px-2" href="Leave_Application.php">Leave Application</a>
        <a class="nav-item nav-link px-2" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
<main>
  <h1 class="text-center text-light py-3">Employee Dashboard</h1>
  <div class="emp_box">

    <div class="emp_box_left">
      <div class="prof_img">
        <img src="assets/images/profile-icon.png">
      </div>
      <div class="emp_profile_box">
        <div class="emp_profile_cont">
          <span>Name:</span>
          <p><?php echo $prof_details['first_name']; ?> <?php echo $prof_details['last_name']; ?></p>
        </div>
        <div class="emp_profile_cont">
          <span>Address:</span>
          <p><?php echo $prof_details['address']; ?></p>
        </div>
        <div class="emp_profile_cont">
          <span>Email:</span>
          <p><?php echo $prof_details['email']; ?></p>
        </div>
        <div class="emp_profile_cont">
          <span>Designation:</span>
          <p><?php echo $prof_details['job_title']; ?></p>
        </div>
      </div>
      
    </div>
    <?php
    $task_data = $pdo->prepare("SELECT * FROM tasks WHERE assigned_to=$emp_id");
    $task_data->execute();?>
    <div class="emp_box_center">
      <h3>Projects & Tasks</h3>
      <div class="emp_tasks">
      <?php while ($task_details = $task_data->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <a href="Task_Details.php?id='<?php echo $task_details['task_id'];?>'" class="emp_task_box">
          <div>
            <h4><?php echo $task_details['task_name']; ?></h4>
            <p class="mb-0"><?php echo $task_details['end_date']; ?></p>
          </div>
          <div class="more_details">
              <span class="mr-2">More Details</span>
              <div style="width:20px;">
                <img src="assets/images/right-arrows.png" class="w-100 d-block">
              </div>
          </div>
        </a>
      <?php
      }
      ?>
      </div>
    </div>

    <div class="emp_box_right">
    <a href="generatepdf.php?id=<?php echo $prof_details['user_id'];?>" class="pay_btn">Generate Payslip</a>
          
    <!-- <button class="pay_btn">Punch In</button>
    <button class="pay_btn">Punch Out</button> -->
    </div>
  </div>
</main>
</body>
<?php                
    }
    ?>
</html>
