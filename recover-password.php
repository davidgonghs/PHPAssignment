<?php
include_once("model/User.php");
include_once("tools/Mysql.php");
if(isset($_POST['submit'])){
    $passwordErr = "";
    $rePaswordErr = "";
    if(empty($_POST['password']) || empty($_POST['repassword'])){
        $passwordErr .= "Password is empty or Retype password is empty .";
    }else{
        $password = $_POST['password'];
        $repassword = $_POST['repassword'];
        if($password == $repassword){
            if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",$password)) {
                $passwordErr .= "Must be at least 8 characters. Must contain at least 1 number . 
                Must contain at least one uppercase character.  
                Must contain at least one lowercase character. ";
            }else{
                $user = new User();
                $user->setPassword(sha1($password));
                $mysql = new Mysql();
                $sql = "user_id = '".$_COOKIE['user_id']."'";
                $mysql->update($user::$db_name,$user,$sql);

            }
        }else{
            $rePaswordErr .= "Password and Retype password is different. ";
        }

        if($passwordErr == '' && $rePaswordErr == ''){
            header("location:login.php");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Recover Password</title>
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<header class="header align-self-center">
    <div class="login-logo">
        <a href="#"><b>FCUC</b> AWD Assignment</a>
    </div>
</header>
<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
        <p class="h1"><b>Recover Password</b></p>
        <p>David_AWD_Assignment</p>
    </div>
    <div class="card-body">
      <p class="login-box-msg">You are only one step a way from your new password, recover your password now.</p>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="input-group mb-3">
          <input type="password" class="form-control <?php echo (!empty($passwordErr)) ? 'is-invalid' : ''; ?>" placeholder="Password" id="password" name="password" >
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
          <div class="invalid-feedback"><?php echo $passwordErr; ?></div>
        </div>
        <div class="input-group mb-3">
          <input type="password" class="form-control <?php echo (!empty($rePaswordErr)) ? 'is-invalid' : ''; ?>" placeholder="Confirm Password" id="repassword" name="repassword">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
            <div class="invalid-feedback"><?php echo $rePaswordErr; ?></div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">Change password</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <p class="mt-3 mb-1">
        <a href="login.php">Login</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->
<footer class="footer text-center">
    <strong>David Gong B1146 @ AWD Assignment</strong>
    <div class="inline-block">
        First City University College
    </div>
</footer>
<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
</body>
</html>
