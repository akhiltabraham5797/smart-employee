<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "sems";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $emailErr = $passwordErr = "";

    if (empty($email)) {
        $emailErr = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailErr = "Invalid email format";
    }

    if (empty($password)) {
        $passwordErr = "Password is required";
    }

    if (empty($emailErr) && empty($passwordErr)) {
        $stmt = $conn->prepare("SELECT user_id, first_name, last_name, phone, password, role, address, date_joined, department, job_title, date_of_birth FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $hashed_password = $row["password"];

            if (password_verify($password, $hashed_password)) {
                $_SESSION["user_id"] = $row["user_id"];
                $_SESSION["firstname"] = $row["first_name"];
                $_SESSION["lastname"] = $row["last_name"];
                $_SESSION["phone"] = $row["phone"];
                $_SESSION["role"] = $row["role"];
                $_SESSION["address"] = $row["address"];
                $_SESSION["date_joined"] = $row["date_joined"];
                $_SESSION["department"] = $row["department"];
                $_SESSION["job_title"] = $row["job_title"];
                $_SESSION["date_of_birth"] = $row["date_of_birth"];

                // Redirect based on user role
                switch ($row["role"]) {
                    case 'HR':
                        header("Location: hr-dashboard.php");
                        break;
                    case 'project manager':
                        header("Location: projectmanager.php");
                        break;
                    case 'employee':
                        header("Location: employee-dashboard.php");
                        break;
                    default:
                        echo "<script>console.error('Unknown role.');</script>";
                }
                exit();
            } else {
                echo "<script>alert('Invalid email or password.');window.location.href = 'login.php';</script>";
            }
        } else {
            echo "<script>alert('Invalid email or password.');window.location.href = 'login.php';</script>";
            exit();
        }

        $stmt->close();
    }
}

$conn->close();
?>
