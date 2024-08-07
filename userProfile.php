<?php
session_start();
// Check if the user is logged in
if(isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
      <div class="navigation-cont">
        <a class="navigation-link" href="employee-dashboard.php">Home</a>
        <a class="navigation-link" href="userProfile.php">Edit Profile</a>
        <a class="navigation-link" href="Leave_Application.php">Leave Application</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4">
                <div class="profile-img">
                    <img src="assets/images/userLogo.png" alt="Profile Image" class="img-thumbnail">
                </div>
            </div>
            <div class="col-md-8">
                <div class="profile-header">
                    <h2>User Profile</h2>
                </div>
                <div class="profile-details">
                    <h4>Personal Information</h4>
                    <p><strong>First Name:</strong> Admin</p>
                    <p><strong>Last Name:</strong> Admin</p>
                    <p><strong>Email:</strong> Admin@example.com</p>
                    <p><strong>Phone:</strong> 123-456-7890</p>
                    <h4>Address</h4>
                    <p>79 dolomiti court, canada</p>
                    <h4>Job Details</h4>
                    <p><strong>Department:</strong> IT</p>
                    <p><strong>Job Title:</strong> Software Developer</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
