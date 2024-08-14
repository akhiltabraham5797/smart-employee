<?php session_start();
require_once 'db_connection.php';
//$_SESSION["pid"] = ""; $_SESSION["qty"] = ""; exit();

$cart = $_SESSION["pid"];

$arraydata = explode(",",$cart); 
$count = count($arraydata)-1; if($count<0)$count = 0;
if(count($arraydata)==1)$count = 1;

$cataid = "";
if(isset($_REQUEST["cataid"])){ $cataid = $_REQUEST["cataid"]; }
if($cataid !="") { $query = " and pp.category_id='".$cataid."' " ;} else { $query = " "; }
$sql = "SELECT * FROM products pp,categories cc where cc.category_id=pp.category_id  $query order by pp.product_name";

$stmt = $pdo->query($sql);
$leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqll = "SELECT *,oo.first_name as fname,oo.last_name as lname FROM order_details oo,users uu where uu.user_id=oo.user_id order by order_id  desc";

$stmtl = $pdo->query($sqll);
$catalist = $stmtl->fetchAll(PDO::FETCH_ASSOC);

$message = "";

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <script>
        function deleteEmployee(leaveId) 
        {
            if(confirm("Are you sure you want to delete employee?"))
            {
                
            }   
        }
    </script>
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
  </nav>
</header>
<main class="container mt-5">
    <h1>Order Details</h1>
    <?php if ($message): ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

        <!-- <div class="row">
            <div class="col-md-4 item item-product-cat">
                <div class="inner">
                    <div class="thumb">
                        <div class="photo" style="background-image:url(assets/uploads/product-featured-113.jpg);"></div>
                        <div class="overlay"></div>
                    </div>
                    <div class="text">
                        <h3><a href="product.php?id=113">Black Forest Honey 220g </a></h3>
                        <h4>
                        ₹399 
                        </h4>
                        <div class="rating">
                        <input type="number" class="input-text qty" step="1" min="1" max="" name="p_qty" value="1" title="Qty" size="4" pattern="[0-9]*" inputmode="numeric">
                        </div>
                        <p><a href="product.php?id=113">Add to Cart</a></p>
                    </div>
                </div>
            </div>
        </div> -->
        <form  name="products" id="products" action="">
   
    <table class="leave-requests-table table-striped">
        <thead>
            <tr>
                <th>User</th>
                <th>Order Number</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Address</th>
                <th>Email-ID</th>
                <th>Phone</th>
                <th>Order Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($catalist)): ?>
                <tr>
                    <td colspan="6" class="text-center">No Products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($catalist as $request): ?>
                    <tr>
                        <td width="15%"><?php echo htmlspecialchars($request['username']); ?></td>
                        <td width="10%"><?php echo htmlspecialchars($request['order_number']); ?></td>
                        <td width="12%"><?php echo htmlspecialchars($request['fname']); ?></td>
                        <td width="15%"><?php echo htmlspecialchars($request['lname']); ?></td>
                        <td width="10%"><?php echo htmlspecialchars($request['address']); ?></td>
                        <td width="15%"><?php echo $request['email']; ?></td>
                        <td width="5%"><?php echo $request['phone']; ?></td>
                        <td align="right" width="10%">$ <?php echo $request['order_total']; ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
                </form>
</main>

<script>
function getCategoryData(val)
{
    window.location = "products.php?cataid="+val;
}

function addProduct(val,pid)
{
    var qty = document.getElementById("qty_"+pid).value;
    window.location = "products.php?cataid="+val+"&pid="+pid+"&qty="+qty;
}
</script>
</body>
</html>

