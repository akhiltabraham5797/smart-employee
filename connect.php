<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sems";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT user_id, first_name, last_name, phone, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashed_password = $row["password"];

        if (password_verify($password, $hashed_password)) {
            $user_id = $row["user_id"];
            $firstname = $row["first_name"];
            $lastname = $row["last_name"];
            $mobilenumber = $row["phone"];

            session_start();
            $_SESSION["user_id"] = $user_id;
            echo "<script>console.log('Login successful. Your user ID is: " . $user_id . "');</script>";
            header("Location: dashboard.php");
            exit();
        } else {
            echo "<script>console.error('Invalid email or password.');</script>";
        }
    } else {
        echo "<script>console.error('Invalid email or password.');</script>";
    }
} else {
    echo "<script>console.error('Email and password are required.');</script>";
}

$conn->close();
?>
