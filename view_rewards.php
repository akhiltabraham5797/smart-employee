<?php
require_once('db_connection.php');
session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $emp_id = $_SESSION['user_id'];
} else {
    header('Location: login.php');
    exit;
}


// Fetch the total points and total redeemed cash for the logged-in user
try {
    $stmt = $pdo->prepare("SELECT points, redeemed_cash FROM rewards WHERE user_id = ?");
    $stmt->execute([$emp_id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        $totalPoints = $result['points'];
        $totalRedeemedCash = $result['redeemed_cash'];
    } 
    // else {
    //     $totalPoints = 0;
    //     $totalRedeemedCash = 0;
    // }
} catch (Exception $e) {
    $error = "Error: " . $e->getMessage();
}

// Handle the redemption process
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $redeemPoints = $_POST['redeem_points'];

    // Validate input
    if ($redeemPoints > $totalPoints) {
        $error = "You do not have enough points to redeem.";
    }
    else {
        try {
            // Deduct points and update the redeemed cash
            $newPoints = $totalPoints - $redeemPoints;
            $newRedeemedCash = $totalRedeemedCash + $redeemPoints;

            $stmt = $pdo->prepare("UPDATE rewards SET points = ?, redeemed_cash = ? WHERE user_id = ?");
            $stmt->execute([$newPoints, $newRedeemedCash, $emp_id]);

            $totalPoints = $newPoints;
            $totalRedeemedCash = $newRedeemedCash;
            $success = "You have successfully redeemed $redeemPoints points.";
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Employee - View and Redeem Reward Points</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
    <nav class="navigation">
        <a class="navigation-logo" href="#">Smart Employee</a>
        <div class="navigation-cont">
            <a class="navigation-link" href="employee-dashboard.php">Home</a>
            <a class="navigation-link" href="employee_view_points.php">View Points</a>
            <a class="navigation-link" href="logout.php">Logout</a>
        </div>
    </nav>
</header>
<main class="rewards">
<div class="reward_container">
<h2>Your Reward Points</h2>

<?php
if (isset($error)) {
    echo "<p class='error'>$error</p>";
} elseif (isset($success)) {
    echo "<p class='success'>$success</p>";
}
?>

<p>You have earned a total of <strong><?= $totalPoints ?></strong> reward points.</p>
<p>Total Redeemed Cash: <strong>$<?= $totalRedeemedCash ?></strong></p>

<h2>Redeem Points</h2>
<form method="POST" action="">
    <label for="redeem_points">Points to Redeem:</label>
    <input type="number" id="redeem_points" name="redeem_points" min="1" max="<?= $totalPoints ?>" required>
    <br>
    <input type="submit" value="Redeem">
</form>
</div>


</main>
</body>
</html>
