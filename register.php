<?php
    include_once("model/User.php");
    include_once("tools/Mysql.php");

    $register = new User();
    $nameErr = "";
    $emailErr = "";
    $phoneErr = "";
    $passErr = "";
    $rePasswordErr = "";
    $agreeTermsErr = "";
    if(isset($_POST['submit'])){

        if(empty($_POST['agreeTerms'])){
            $agreeTermsErr = "Terms is empty. ";
        }

        /*check name*/
        if(empty($_POST['name'])){
            $nameErr = "Name is empty";
        }else{
            $name = $_POST['name'];
            if (!preg_match("/^[a-zA-Z ]*$/",$name)) {
                $nameErr = "Only letters and spaces are allowed in the name. ";
            }
            $register->setName($name);
        }

        /*check email*/
        if (empty($_POST["email"])) {
            $emailErr = "Email is empty";
        } else {
            $email = $_POST["email"];
            if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
                $emailErr = "Illegal mailbox format. ";
            }else{
                $register->setEmail($_POST["email"]);

            }
        }

        if (empty($_POST["phone"])) {
            $phoneErr = "Phone is empty";
        } else {
            $phone = $_POST["phone"];
            if (!preg_match("/^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/",$phone)) {
                $phoneErr = "Illegal phone number. ";
            }else{
                $register->setPhone($phone);
            }
        }

        /*check password*/
        if(empty($_POST['password']) || empty($_POST['repassword'])){
            $passErr = "Password is empty or Retype password is empty .";
        }else{
            $password = $_POST['password'];
            $repassword = $_POST['repassword'];
            if($password == $repassword){
                if (!preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/",$password)) {
                    $passErr = "Must be at least 8 characters. 
                                Must contain at least 1 number .
                                Must contain at least one uppercase character. 
                                Must contain at least one lowercase character. ";
                }else{
                    $register->setPassword(sha1($password));
                }
            }else{
                $rePasswordErr = "Password and Retype password is different. ";
            }
        }

        if($nameErr == "" && $emailErr == "" && $phoneErr == "" && $passErr == "" && $rePasswordErr == "" && $agreeTermsErr == "" ){
            $register->setUserId(uniqid());
            $mysql = new Mysql();
            $mysql->insert($register::$db_name,$register);
            header("location:index.php?user_id=".$register->getUserId());
        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta  http-equiv="content-type" content="text/html;charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Page</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition register-page">
<header class="header align-self-center">
    <div class="login-logo">
        <a href="#"><b>FCUC</b> AWD Assignment</a>
    </div>
</header>
<div class="register-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center">
            <p class="h1"><b>Registration</b></p>
            <p>David_AWD_Assignment</p>
        </div>
        <div class="card-body">
            <p class="login-box-msg">Register a new membership</p>

            <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" >
                <div class="input-group mb-3">
                    <input type="text" class="form-control  <?php echo (!empty($nameErr)) ? 'is-invalid' : ''; ?>" placeholder="Full name" id="name" name="name" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo $nameErr; ?></div>
                </div>

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
                    <input type="text" class="form-control <?php echo (!empty($phoneErr)) ? 'is-invalid' : ''; ?>" placeholder="Phone" id="phone" name="phone" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-phone"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo $phoneErr; ?></div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control <?php echo (!empty($passErr)) ? 'is-invalid' : ''; ?>" placeholder="Password" id="password" name="password" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo $passErr; ?></div>
                </div>

                <div class="input-group mb-3">
                    <input type="password" class="form-control <?php echo (!empty($rePasswordErr)) ? 'is-invalid' : ''; ?>" placeholder="Retype password" id="repassword" name="repassword" >
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                    <div class="invalid-feedback"><?php echo $rePasswordErr; ?></div>
                </div>

                <div class="row">
                    <div class="col-8">
                        <div class="custom-control custom-checkbox icheck-primary">
                            <input type="checkbox" class="custom-control-input" id="agreeTerms" name="agreeTerms" value="1" required>
                            <label for="agreeTerms">
                                I agree to the <a href="terms.html">terms</a>
                            </label>
                            <div class="invalid-feedback"><?php echo $agreeTermsErr; ?></div>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" name="submit" value="Submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>
            <a href="login.php" class="text-center">I already have a membership</a>
        </div>
        <!-- /.form-box -->
    </div><!-- /.card -->
</div>
<!-- /.register-box -->
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

