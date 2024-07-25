<!DOCTYPE html>
<html lang="en">

<head>
  <title>Smart Employee | Login</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
  <!-- link for css file -->
  <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="index-page">
  <section id="wraper-login-page">
    <div class="container">
      <div class="row">
        <div class="col-md-12">
          <div class="wrap-login-top">
            <h3>Login</h3>
            <?php
            ?>
            <form action="connect.php" method="post" id="form-t-box">
              <div class="form-group-t">
                <label for="email" class="text-uppercase text-sm">Enter Email</label>
                <input type="email" id="email" placeholder="Username" name="email" class="form-control mb">
                <span class="text-danger"><?php echo $emailErr ?? ''; ?></span>
              </div>
              <div class="form-group-t">
                <label for="password" class="text-uppercase text-sm">Enter Password</label>
                <input type="password" id="password" placeholder="Password" name="password" class="form-control mb">
                <span class="text-danger"><?php echo $passwordErr ?? ''; ?></span>
              </div>
              <input type="submit" name="login" value="Login" class="btn btn-primary btn-block">
            </form>
            <div class="warps-new-usr">
              <h2 class="ots-prr">New User? <a href="signup.php">Create Account</a></h2>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</body>
</html>
