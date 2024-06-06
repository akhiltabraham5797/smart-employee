<!DOCTYPE html>
<html lang="en">

<head>
    <title>Smart Employee | Signup</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <!-- link for css file -->
    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body class="index-page">
    <section id="wrapper-signup-page">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="signup-top-wrapper">
                        <h3>Sign Up</h3>

                        <?php
                        $username = $password = $firstname = $lastname = $email = $mobilenumber = "";
                        $usernameErr = $passwordErr = $firstnameErr = $lastnameErr = $emailErr = $mobilenumberErr = "";

                        if ($_SERVER["REQUEST_METHOD"] == "POST") {
                            if (empty($_POST["firstname"])) {
                                $firstnameErr = "First Name is required";
                            } else {
                                $firstname = $_POST["firstname"];
                            }

                            if (empty($_POST["lastname"])) {
                                $lastnameErr = "Last Name is required";
                            } else {
                                $lastname = $_POST["lastname"];
                            }

                            if (empty($_POST["email"])) {
                                $emailErr = "Email is required";
                            } else {
                                $email = $_POST["email"];
                                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                                    $emailErr = "Invalid email format";
                                }
                            }

                            if (empty($_POST["password"])) {
                                $passwordErr = "Password is required";
                            } else {
                                $password = $_POST["password"];
                            }

                            if (empty($_POST["mobilenumber"])) {
                                $mobilenumberErr = "Mobile Number is required";
                            } else {
                                $mobilenumber = $_POST["mobilenumber"];
                                if (!preg_match('/^[0-9]{10}$/', $mobilenumber)) {
                                    $mobilenumberErr = "Invalid mobile number format";
                                }
                            }

                            if (empty($firstnameErr) && empty($lastnameErr) && empty($emailErr) && empty($passwordErr) && empty($mobilenumberErr)) {
                                // Database connection settings
                                $servername = "localhost";
                                $db_username = "root";
                                $db_password = "";
                                $dbname = "sems";

                                // Create connection
                                $conn = new mysqli($servername, $db_username, $db_password, $dbname);

                                // Check connection
                                if ($conn->connect_error) {
                                    die("Connection failed: " . $conn->connect_error);
                                }

                                // Hash the password
                                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                                // Prepare and bind the SQL statement
                                $stmt = $conn->prepare("INSERT INTO users (username, password, first_name, last_name, email, phone) VALUES (?, ?, ?, ?, ?, ?)");
                                $stmt->bind_param("ssssss", $email, $hashed_password, $firstname, $lastname, $email, $mobilenumber);

                                // Execute the statement
                                if ($stmt->execute()) {
                                    echo "<script>alert('Registration Successful');</script>";
                                } else {
                                    echo "<script>alert('Error registering user');</script>";
                                }

                                $stmt->close();
                                $conn->close();
                            }
                        }
                        ?>

                        <form method="post" id="signup-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="firstname" class="text-uppercase text-sm">Enter First Name</label>
                                <input type="text" id="firstname" placeholder="First Name" name="firstname" class="form-control mb" value="<?php echo htmlspecialchars($firstname); ?>">
                                <span class="text-danger"><?php echo $firstnameErr; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="lastname" class="text-uppercase text-sm">Enter Last Name</label>
                                <input type="text" id="lastname" placeholder="Last Name" name="lastname" class="form-control mb" value="<?php echo htmlspecialchars($lastname); ?>">
                                <span class="text-danger"><?php echo $lastnameErr; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="email" class="text-uppercase text-sm">Enter Email</label>
                                <input type="email" id="email" placeholder="Email" name="email" class="form-control mb" value="<?php echo htmlspecialchars($email); ?>">
                                <span class="text-danger"><?php echo $emailErr; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="password" class="text-uppercase text-sm">Enter Password</label>
                                <input type="password" id="password" placeholder="Password" name="password" class="form-control mb">
                                <span class="text-danger"><?php echo $passwordErr; ?></span>
                            </div>

                            <div class="form-group">
                                <label for="mobilenumber" class="text-uppercase text-sm">Enter Mobile Number</label>
                                <input type="number" id="mobilenumber" placeholder="Mobile Number" name="mobilenumber" class="form-control mb" value="<?php echo htmlspecialchars($mobilenumber); ?>">
                                <span class="text-danger"><?php echo $mobilenumberErr; ?></span>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">Sign Up</button>
                        </form>

                        <div class="existing-user-wrapper">
                            <h2 class="existing-user-text">Already have an account? <a href="login.php">Log In</a></h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

</html>
