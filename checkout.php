<?php session_start();
require_once 'db_connection.php';

if(isset($_POST['submit']))
{
  if (isset($_SESSION['user_id'])) 
  {
    $emp_id = $_SESSION['user_id'];
  }
  if ($_SERVER["REQUEST_METHOD"] == "POST") 
  {
    $user_id = $emp_id;
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $address = $_POST['address'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $requested_on = date("Y-m-d");
    $order_number = strtotime(date("Y-m-d H:i:s"));
    $create_date = date("Y-m-d H:i:s");

    $pid = $_SESSION["pid"];
    $qty = $_SESSION["qty"];
    $parraydata = explode(",",$pid); 
    $qarraydata = explode(",",$qty); 
    $message = "";
    $parraydata = array_filter($parraydata);
    $qarraydata = array_filter($qarraydata);
    $gtotal = 0;
    foreach ($parraydata as $rs)
    {
      $product_id = $rs;
      
      $sql = "SELECT * FROM products WHERE product_id = ?";
      $stmt = $pdo->prepare($sql);
      $stmt->execute([$rs]);
      $request = $stmt->fetch(PDO::FETCH_ASSOC);
      $index = array_search($rs, $parraydata);
      $qty = $qqty = $qarraydata[$index];
      $price = $request['product_price'];
      $tprice = ($qqty*$request['product_price']);
      $gtotal = $tprice + $gtotal;
      $sqllll = "INSERT INTO order_details_products (order_number, product_id, qty, price)
            VALUES (:order_number, :product_id, :qty, :price)";
            
      $stmt = $pdo->prepare($sqllll);
      $stmt->bindParam(':order_number', $order_number);
      $stmt->bindParam(':product_id', $product_id);
      $stmt->bindParam(':qty', $qty);
      $stmt->bindParam(':price', $price);
      $stmt->execute();
    }
    $order_total = $gtotal;
    $sqlff = "INSERT INTO order_details (user_id, order_number, create_date, first_name, last_name, address, email,phone,order_total)
            VALUES (:user_id, :order_number, :create_date, :first_name, :last_name, :address, :email, :phone,:order_total)";
    
    try {
        $stmt = $pdo->prepare($sqlff);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':order_number', $order_number);
        $stmt->bindParam(':create_date', $create_date);
        $stmt->bindParam(':first_name', $first_name);
        $stmt->bindParam(':last_name', $last_name);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':order_total', $order_total);
        $stmt->execute();
        $message = "Successfully Ordered.";
        $_SESSION["pid"] = ""; $_SESSION["qty"] = "";
        header("Location: orders-list.php");
                exit;
    } catch (PDOException $e) {
        $message = "Error: " . $e->getMessage();
    }

    $pdo = null; 
}
}

?><!DOCTYPE html>
<html lang="en">
<head>
  <title>Smart Employee | Checkout</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="index-page emp_dashboard">
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
    <?php if($_SESSION["role"] == "HR"){ ?>
    <div class="navigation-cont">
        <a class="navigation-link" href="hr-dashboard.php">Home</a>
        <a class="navigation-link" href="products.php">Shoping</a>
        <a class="navigation-link" href="HR_Manage_Leave_Request.php">Leave Approval</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
    <?php } else if($_SESSION["role"] == "project manager"){ ?>
    <div class="navigation-cont">
        <a class="navigation-link" href="projectmanager.php">Home</a>
        <a class="navigation-link" href="management.php">Create Project</a>
        <a class="navigation-link" href="products.php">Shoping</a>
        <a class="navigation-link" href="Manage_Leave_Requests.php">Leave Approval</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    <?php } else if($_SESSION["role"] == "employee"){ ?>
        <div class="navigation-cont">
        <a class="navigation-link" href="employee-dashboard.php">Home</a>
        <a class="navigation-link" href="userProfile.php">Edit Profile</a>
        <a class="navigation-link" href="Leave_Application.php">Leave Application</a>
        <a class="navigation-link" href="salary_generation.php">Pay Details</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    <?php } ?>
    </div>
  </nav>
</header>

<div class="container2">
  <div class="box">


    <h1>Cart Checkout</h1>
    <form method="POST" action="">
      <div>
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" value="" required>
      </div>
      <div>
        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" value="" required>
      </div>
      <div>
        <label for="address">Address:</label>
        <input type="text" id="address" name="address" value="" required>
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="" required>
      </div>
      <div>
        <label for="email">Phone:</label>
        <input type="text" id="phone" name="phone" value="" required>
      </div>
      <div>
        <button type="submit"  name="submit"  id="submit">Submit</button>
      </div>
    </form>
  
  </div>
</div>
</body>
</html>
