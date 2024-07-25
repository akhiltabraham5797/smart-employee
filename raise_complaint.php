<?php
session_start();
require_once('db_connection.php'); 

$message = "";

if (isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{
    $user_id = $emp_id;
    $description = $_POST['description'];
    $requested_on = date("Y-m-d");

    $sql = "INSERT INTO complaints (user_id, complaint_description, raised_on) VALUES (:user_id,:description,:requested_on)";
    
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':requested_on', $requested_on);
        $stmt->execute();
        $message = "New Complaint request submitted successfully";
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }

    $pdo = null; 
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Apply for Leave</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var message = "<?php echo $message; ?>";
            if (message) 
            {
                alert(message);
                window.location = "employee-dashboard.php";
            }
        });
    </script>
</head>
<body class="index-page">
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
<main class="leavebody">
    <div class="leavecontainer leave-application-container">
        <h1>Raise Complaint</h1>
        <form id="leave-form" method="post" action="raise_complaint.php">
            <input type="hidden" name="user_id" value="<?php echo $emp_id; ?>"> 

            <label for="description">Complaint Description:</label>
            <textarea id="description" name="description" rows="4" placeholder="Brief description about the Complaint" required></textarea>

            <button type="submit">Submit</button>
        </form>
    </div>
</main>
</body>
</html>
