<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== 'admin') {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_POST['user_id'];
    $points = $_POST['points'];
    $note = $_POST['note'];
    $awardedBy = $_SESSION["user_id"]; // Admin ID stored as user_id

    try {
        $stmt = $pdo->prepare("INSERT INTO rewards (user_id, points, appreciation_note, awarded_by) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $points, $note, $awardedBy]);
        $success = "Reward points and appreciation note have been successfully awarded.";
    } catch (Exception $e) {
        $error = "Error: " . $e->getMessage();
    }
}

// Fetch all users for the dropdown
$usersStmt = $pdo->query("SELECT user_id, first_name FROM users");
$users = $usersStmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Award Appreciation and Reward Points</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <nav class="navigation">
        <a class="navigation-logo" href="#">Smart Employee</a>
        <div class="navigation-cont">
            <a class="navigation-link" href="admin-dashboard.php">Home</a>
            <a class="navigation-link" href="admin_rewards.php">Award Points</a>
            <a class="navigation-link" href="logout.php">Logout</a>
        </div>
    </nav>
</header>
<main>
<div class="leavecontainer leave-application-container">
<h2>Award Appreciation and Reward Points</h2>
    <form  method="POST" action="">
        <label for="user_id">Select Employee:</label>
        <select id="user_id" name="user_id" required>
            <option value="">Select an employee</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user['user_id'] ?>"><?= htmlspecialchars($user['first_name']) ?></option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="points">Reward Points:</label>
        <input type="number" id="points" name="points" required min="0">
        <br>
        <label for="note">Appreciation Note:</label>
        <textarea id="note" name="note" rows="4"></textarea>
        <br>
        <input type="submit" value="Award">
    </form>

    <?php
    if (isset($success)) {
        echo "<p class='success'>$success</p>";
    } elseif (isset($error)) {
        echo "<p class='error'>$error</p>";
    }
    ?>
</div>
    
</main>
</body>
</html>
