<?php

include_once("model/User.php");
include_once("tools/Mysql.php");

if(isset($_POST['submit'])){
    $user = new User();
    $errInfor = "";
    /*check email*/
    if (empty($_POST["email"])) {
        $errInfor = "Email is empty. ";
    } else {
        $email = $_POST["email"];
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)){
            $errInfor = "Illegal mailbox format. ";
        }else{
            $user->setEmail($email);
        }
    }

    if(!$errInfor == ''){
        jsAlert($errInfor);
    }

    $db = new Mysql();
    $result = $db -> select_one($user::$db_name,'',$user);
    if(!empty($result)){
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_assoc()) {
                foreach ($row as $k => $v) {
                    if(!empty($_COOKIE[$k])){
                        setcookie($k,$v,time()+3600);
                    }else{
                        setcookie($k,$v,time()+3600);
                    }
                }
            }
        }else{
            $errInfor .= " Can't find user. ";

        }
    } else {
        $errInfor .= "Can't find user.";
    }

    if($errInfor == ''){
        header("location:recover-password.php");
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Forgot Password</title>

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
        <p class="h1"><b>Forgot Password</b></p>
        <p>David_AWD_Assignment</p>
    </div>

    <div class="card-body">
      <p class="login-box-msg">You forgot your password? Here you can easily retrieve a new password.</p>
      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <div class="input-group mb-3">
          <input type="email" class="form-control <?php echo (!empty($errInfor)) ? 'is-invalid' : ''; ?>" placeholder="Email" id="email" name="email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
            <div class="invalid-feedback"><?php echo $errInfor; ?></div>
        </div>
        <div class="row">
          <div class="col-12">
            <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">Request new password</button>
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
