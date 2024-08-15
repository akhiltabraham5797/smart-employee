<?php 
session_start();
require_once 'db_connection.php';

$count = 0; 
$cart = ''; 
$ppid = $pqty = $pppid = $ppqty = "";

$pid = isset($_SESSION["pid"]) ? $_SESSION["pid"] : '';
$qty = isset($_SESSION["qty"]) ? $_SESSION["qty"] : '';
$parraydata = explode(",", $pid); 
$qarraydata = explode(",", $qty); 
$message = "";
$parraydata = array_filter($parraydata);
$qarraydata = array_filter($qarraydata);

if (isset($_GET['checkout']) && $_GET['checkout'] == 'done') {
    // Clear the cart
    $_SESSION["pid"] = ""; 
    $_SESSION["qty"] = "";
    $message = "Cart has been cleared after checkout.";
}

if (isset($_GET['delete'])) {
    $deleteId = intval($_GET['delete']);
    
    $index = array_search($deleteId, $parraydata);
    if ($index !== false) {
        array_splice($parraydata, $index, 1);
        array_splice($qarraydata, $index, 1);
        
        $_SESSION["pid"] = implode(",", $parraydata);
        $_SESSION["qty"] = implode(",", $qarraydata);
        
        $message = "Item has been removed from the cart.";
    } else {
        $message = "Item not found in the cart.";
    }
}

$gtotal = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header>
  <nav class="navigation">
    <a class="navigation-logo" href="#">Smart Employee</a>
    <div>
    <?php if($_SESSION["role"] == "HR"){ ?>
    <div class="navigation-cont">
        <a class="navigation-link" href="hr-dashboard.php">Home</a>
        <a class="navigation-link" href="HR_Manage_Leave_Request.php">Leave Approval</a>
        <a class="navigation-link" href="products.php">Shoping</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    </div>
    <?php } else if($_SESSION["role"] == "project manager"){ ?>
    <div class="navigation-cont">
        <a class="navigation-link" href="projectmanager.php">Home</a>
        <a class="navigation-link" href="management.php">Create Project</a>
        <a class="navigation-link" href="Manage_Leave_Requests.php">Leave Approval</a>
        <a class="navigation-link" href="products.php">Shoping</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    <?php } else if($_SESSION["role"] == "employee"){ ?>
        <div class="navigation-cont">
        <a class="navigation-link" href="employee-dashboard.php">Home</a>
        <a class="navigation-link" href="edit_profile.php">Edit Profile</a>
        <a class="navigation-link" href="Leave_Application.php">Leave Application</a>
        <a class="navigation-link" href="salary_generation.php">Pay Details</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    <?php } ?>
  </nav>
</header>
<main class="container mt-5">
    <h1>View Cart Details</h1>
    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form name="products" id="products" action="">
        <table class="leave-requests-table table-striped">
            <thead>
                <tr>
                    <th>Product Image</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Product Category</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($parraydata)): ?>
                    <tr>
                        <td colspan="8" class="text-center">No Products found.</td>
                    </tr>
                <?php else: ?>
                    <?php 
                    foreach ($parraydata as $rs): 
                        $sql = "SELECT * FROM products pp, categories cc WHERE cc.category_id=pp.category_id AND product_id = ?";
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute([$rs]);
                        $request = $stmt->fetch(PDO::FETCH_ASSOC);
                        $index = array_search($rs, $parraydata);
                        $qqty = isset($qarraydata[$index]) ? $qarraydata[$index] : 0;
                        $tprice = ($qqty * $request['product_price']);
                        $gtotal += $tprice;
                    ?>
                        <tr>
                            <td width="15%"><img src="product_images/<?php echo htmlspecialchars($request['product_image']); ?>" width="80px" height="50px" /></td>
                            <td width="10%"><?php echo htmlspecialchars($request['product_code']); ?></td>
                            <td width="20%"><?php echo htmlspecialchars($request['product_name']); ?></td>
                            <td width="15%"><?php echo htmlspecialchars($request['category_name']); ?></td>
                            <td width="10%">$ <?php echo htmlspecialchars($request['product_price']); ?></td>
                            <td width="10%"> <?php echo $qqty; ?></td>
                            <td width="10%">$ <?php echo $tprice; ?></td>
                            <td width="10%"><a href="?delete=<?php echo $rs; ?>" class="btn btn-danger btn-sm">Remove</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                <tr><td colspan="6" align="right"><b>Grand Total</b></td><td><b>$ <?php echo $gtotal; ?></b></td></tr>
                <tr><td colspan="5" align="right">&nbsp;</td><td colspan="2"><a href="checkout.php"><button type="button" class="btn btn-success btn-sm mt-2">Proceed to Checkout</button></a></td></tr>
            </tbody>
        </table>
    </form>
</main>

<script>
function getCategoryData(val) {
    window.location = "products.php?cataid=" + val;
}

function addProduct(val, pid) {
    var qty = document.getElementById("qty_" + pid).value;
    window.location = "products.php?cataid=" + val + "&pid=" + pid + "&qty=" + qty;
}
</script>
</body>
</html>
