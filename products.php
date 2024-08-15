<?php session_start();
require_once 'db_connection.php';
//$_SESSION["pid"] = ""; $_SESSION["qty"] = ""; exit();
$count = 0; $cart =''; $ppid = $pqty = $pppid = $ppqty = "";
if(isset($_REQUEST["pid"]))
{ 
    $pid = $_REQUEST["pid"]; $qty = $_REQUEST["qty"]; 
    if(isset($_SESSION["pid"]))
    {
        $ppid = $_SESSION["pid"];
        $pqty = $_SESSION["qty"];
        if($ppid !="")
        {
            $parraydata = explode(",",$ppid); 
            $qarraydata = explode(",",$ppid); 
            $parraydata = array_filter($parraydata);
            $qarraydata = array_filter($qarraydata);

            if(in_array($pid,$parraydata))
            {
                foreach($parraydata as $res)
                {
                    if($res !="")
                    {
                            $pppid .= ','.$res;
                            $index = array_search($res, $parraydata);
                            //print_r($index);
                            if($res == $pid)
                            {
                                $ppqty .= ','.$qty;
                                //echo "----------------------------------YES--------------------<br>"; //exit();
                            }
                            else
                            {
                                
                                $ppqty .= ','.$qarraydata[$index];
                                //echo "----------------------------------NOT--------------------<br>"; //exit();
                            }
                    }
                }
            }
            else
            {
                $pppid .= $ppid.','.$pid;
                $ppqty .= $pqty.','.$qty;
            }
            
        }
        else
        {
            //echo "----------------------------------Count 1--------------------<br>"; //exit();
            $pppid = $pid;
            $ppqty = $qty;
        }
    }
    else
    {
        $pppid = $pid;
        $ppqty = $qty;  
    }
    //echo "<br>============================PID: ".
    $_SESSION["pid"] = $pppid;
    //echo "<br>============================QTY: ".
    $_SESSION["qty"] = $ppqty;
}
$cart = $_SESSION["pid"];
if($cart !="")
{
    $arraydata = explode(",",$cart); 
    $count = count($arraydata)-1; if($count<0)$count = 0;
    if(count($arraydata)==1)$count = 1;
}


$cataid = "";
if(isset($_REQUEST["cataid"])){ $cataid = $_REQUEST["cataid"]; }
if($cataid !="") { $query = " and pp.category_id='".$cataid."' " ;} else { $query = " "; }
$sql = "SELECT * FROM products pp,categories cc where cc.category_id=pp.category_id  $query order by pp.product_name";

$stmt = $pdo->query($sql);
$leave_requests = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sqll = "SELECT * FROM categories order by category_name";

$stmtl = $pdo->query($sqll);
$catalist = $stmtl->fetchAll(PDO::FETCH_ASSOC);

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leave_id = $_POST['leave_id'];
    $status = $_POST['status'];
    $description = $_POST['description'];

    $update_sql = "UPDATE LeaveRequests SET status = :status, description = :description WHERE leave_id = :leave_id";
    $stmt = $pdo->prepare($update_sql);
    $stmt->bindParam(':status', $status);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':leave_id', $leave_id);

    if ($stmt->execute()) {
        $message = "Leave request updated successfully";
        header("Refresh:0");
    } else {
        $message = "Error updating leave request: " . $stmt->errorInfo()[2];
    }
}
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
        <a class="navigation-link" href="edit_profile.php">Edit Profile</a>
        <a class="navigation-link" href="Leave_Application.php">Leave Application</a>
        <a class="navigation-link" href="salary_generation.php">Pay Details</a>
        <a class="navigation-link" href="logout.php">Logout</a>
      </div>
    <?php } ?>
  </nav>
</header>
<main class="container mt-5">
    <h1>Products Details</h1>
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
        <p align="center">
            <select name="category" id="category" onChange="getCategoryData(this.value);">
                <option value="">---Select Category---</option>
                <?php foreach ($catalist as $request): ?>
                    <option value="<?php echo $request['category_id']; ?>" <?php if($cataid == $request['category_id'] ) echo "selected"; else echo "";  ?>><?php echo $request['category_name']; ?></option>
                <?php endforeach; ?>
            </select>
        </p>
        <p align="right"><a href="viewcart.php">View Cart( <?php echo $count;?> )</a></p>
    <table class="leave-requests-table table-striped">
        <thead>
            <tr>
                <th>Product Image</th>
                <th>Product Code</th>
                <th>Product Name</th>
                <th>Product Category</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($leave_requests)): ?>
                <tr>
                    <td colspan="6" class="text-center">No Products found.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($leave_requests as $request): ?>
                    <tr>
                        <td width="15%"><img src="product_images/<?php echo htmlspecialchars($request['product_image']); ?>" width="80px"  height="50px" /></td>
                        <td width="10%"><?php echo htmlspecialchars($request['product_code']); ?></td>
                        <td width="20%"><?php echo htmlspecialchars($request['product_name']); ?></td>
                        <td width="15%"><?php echo htmlspecialchars($request['category_name']); ?></td>
                        <td width="10%">$ <?php echo htmlspecialchars($request['product_price']); ?></td>
                        <td width="15%"><input type="number" name="qty_<?php echo $request['product_id']; ?>" id="qty_<?php echo $request['product_id']; ?>" value="1"  size="4"  min="1" max="100" ></td>
                        <td width="5%">
                        <a href="javascript:addProduct('<?php echo $cataid; ?>','<?php echo $request['product_id']; ?>');"><button type="button" class="btn btn-success btn-sm mt-2">Add</button></a>
                        <!-- <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteEmployee('<?php echo $request['product_id']; ?>')">Delete</button> -->
                        </td>
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

