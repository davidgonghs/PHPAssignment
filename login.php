
<?php
    include_once("model/User.php");
    include_once("tools/Mysql.php");
    if(isset($_POST['submit'])){

        $user = new User();
        $emailErr = "";
        $passErr = "";
        /*check email*/
        if (empty($_POST["email"])) {
            $emailErr = "Email is empty.";
        } else {
            $user->setEmail($_POST["email"]);
        }

        if(empty($_POST['password'])){
            $passErr = "Password is empty.";
        }else{
            $user->setPassword(sha1($_POST['password']));
        }
        if($emailErr == "" && $passErr == ""){
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
                    header("location:index.php?user_id=".$_COOKIE['user_id']);
                }else{
                    $emailErr="Can't find user or wrong password";
                }
            } else {
                $emailErr="Can't find user or wrong password";
            }
        }
    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Page</title>

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
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <p class="h1"><b>Login</b></p>
            <p>David Gong AWD Assignment</p>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Sign in to start your session</p>

            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="input-group mb-3">
                    <input type="email" class="form-control <?php echo (!empty($emailErr)) ? 'is-invalid' : ''; ?>" placeholder="Email" id="email" name="email" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo $emailErr; ?></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control <?php echo (!empty($passErr)) ? 'is-invalid' : ''; ?>" placeholder="Password" id="password" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo $passErr; ?></div>
                </div>
                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" id="remember">
                            <label for="remember">
                                Remember Me
                            </label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">Sign In</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <!-- /.social-auth-links -->
            <p class="mb-1">
                <a href="forgot-password.php">I forgot my password</a>
            </p>
            <p class="mb-0">
                <a href="register.php" class="text-center">Register a new membership</a>
            </p>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
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
