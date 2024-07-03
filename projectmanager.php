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
  <title>Smart Employee | Project Manager Dashboard</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

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
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
      <div class="navigation-cont">
        <a class="navigation-link" href="employee-dashboard.php">Home</a>
        <a class="navigation-link" href="#">Create new project</a>
        <a class="navigation-link" href="#">Approve levave application</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
