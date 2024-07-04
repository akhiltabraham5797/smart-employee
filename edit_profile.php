<!DOCTYPE html>
<html lang="en">
<head>
  <title>Smart Employee | User Profile Edit</title>
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
        <a class="navigation-link" href="projectmanager.php">Home</a>
        <a class="navigation-link" href="management.php">Create Project</a>
        <a class="navigation-link" href="Manage_Leave_Requests.php">Leave Approval</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
  </nav>
</header>

<div class="container2">
  <div class="box">
    <?php
    // Start session and check if the user is logged in
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }

    require_once('db_connection.php');

    $emp_id = $_SESSION['user_id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $email = $_POST['email'];
        $job_title = $_POST['job_title'];

        // Update profile in the database
        $sql = "UPDATE users SET first_name = ?, last_name = ?, address = ?, email = ?, job_title = ? WHERE user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$first_name, $last_name, $address, $email, $job_title, $emp_id]);

        header('Location: projectmanager.php?update=success');
        exit;
    }

    // Fetch current user details
    $sql = "SELECT * FROM users WHERE user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$emp_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>
    <h1>Edit Profile</h1>
    <form method="POST" action="edit_profile.php">
      <div>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
      </div>
      <div>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
      </div>
      <div>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="<?php echo htmlspecialchars($user['address']); ?>" required>
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
      </div>
      <div>
        <label for="job_title">Job Title:</label>
        <input type="text" id="job_title" name="job_title" value="<?php echo htmlspecialchars($user['job_title']); ?>" required>
      </div>
      <div>
        <button type="submit">Save Changes</button>
      </div>
    </form>
  </div>
</div>
</body>
</html>
