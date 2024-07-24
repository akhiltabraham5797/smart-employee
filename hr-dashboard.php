<?php
// Database connection
require_once('db_connection.php');
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit;
}

// Fetch user profile details
$profile_data = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$profile_data->execute([$emp_id]);
if ($prof_details = $profile_data->fetch(PDO::FETCH_ASSOC)) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>Smart Employee | Project Manager Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="index-page hr_dashboard">
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
  
  <h1 class="emp_head">HR Manager Dashboard</h1>
  <div class="emp_box">
    <div class="emp_box_left">
      <div class="prof_img">
        <img src="assets/images/profile-icon.png">
      </div>
      <div class="partition">
        <div class="emp_profile_box">
          <div class="emp_profile_cont">
            <label>Name:</label>
            <p><?php echo htmlspecialchars($prof_details['first_name']); ?> <?php echo htmlspecialchars($prof_details['last_name']); ?></p>
          </div>
          <div class="emp_profile_cont">
            <label>Address:</label>
            <p><?php echo htmlspecialchars($prof_details['address']); ?></p>
          </div>
          <div class="emp_profile_cont">
            <label>Email:</label>
            <p><?php echo htmlspecialchars($prof_details['email']); ?></p>
          </div>
          <div class="emp_profile_cont">
            <label>Designation:</label>
            <p><?php echo htmlspecialchars($prof_details['job_title']); ?></p>
          </div>
          <a href="edit_profile.php" class="editbutton">Edit profile</a>
        </div>
      </div>
    </div>
    
    <div class="hr_box_center">
        <div class="hr_task_box">
            <a href="" class="hr_task_left">
                <span>Employee Details</span>
            </a>
            <a href="" class="hr_task_right">
                <span>Salary & Attendance</span>
            </a>
        </div>
        <div class="hr_task_box">
            <a href="" class="hr_task_left">
                <span>Leave Approval</span>
            </a>
            <a href="" class="hr_task_right">
            <span>Complaints & Suggestions</span>
            </a>
        </div>
        
        
    </div>
  </div>
</main>
</body>
</html>
<?php } else {
    echo "Profile not found.";
} ?>
