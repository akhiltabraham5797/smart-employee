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
  <title>Smart Employee | Admin Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="index-page emp_dashboard">
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
      <div class="navigation-cont">
        <a class="navigation-link" href="admin_dashboard.php">Home</a>
        <a class="navigation-link" href="appreciation_rewards.php">Rewards</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
<main>
  
  <h1 class="emp_head">Admin Dashboard</h1>
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
    
    <div class="pro_box_center">
      <h3>Projects</h3>
      <div class="emp_tasks">
        <?php
        $proj_data = $pdo->prepare("SELECT * FROM projects");
        $proj_data->execute();
        while ($proj_details = $proj_data->fetch(PDO::FETCH_ASSOC)) {
        ?>
          <a href="Admin_project_tracking.php?project_id=<?php echo $proj_details['project_id']; ?>" class="emp_task_box">
            <div>
              <h4><?php echo htmlspecialchars($proj_details['project_name']); ?></h4>
              <p class="mb-0"><?php echo htmlspecialchars($proj_details['description']); ?></p>
            </div>
            <div class="more_details">
              <span class="mr-2">More Details</span>
              <div style="width:20px;">
              <img src="assets/images/right-arrows.png">
              </div>
            </div>
          </a>
        <?php } ?>
      </div>
    </div>
  </div>
</main>
</body>
</html>
<?php } else {
    echo "Profile not found.";
} ?>
