<?php
function logout(){
    header("location: login.php");
    exit;
}

if(!isset($_COOKIE['user_id']) ){
    logout();
}

include_once("model/Book.php");
include_once("model/BookType.php");
include_once("model/User.php");
include_once ("model/Place.php");
include_once ("model/Shelf.php");
include_once ("tools/Page.php");
include_once ("tools/Common.php");
include_once("tools/Mysql.php");
$errInfor = "";
$db = new Mysql();
$common = new Common();


//$myLogout = logout();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>David Assignment</title>
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" type="text/css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" type="text/css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" type="text/css">
    <!-- Tempusdominus Bootstrap 4 -->
    <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css" type="text/css">
    <!-- iCheck -->
    <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css" type="text/css">
    <!-- JQVMap -->
    <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css" type="text/css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" type="text/css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css" type="text/css">
    <!-- Daterange picker -->
    <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css" type="text/css">
    <!-- summernote -->
    <link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css" type="text/css">
    <!-- summernote -->
    <!--img-->
    <link rel="stylesheet" href="plugins/bootstrap/css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="plugins/img/cropper.min.css" type="text/css">
    <link rel="stylesheet" href="plugins/img/main.css" type="text/css">
    <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>


</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="index.php" class="nav-link">Home</a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                    <i class="fas fa-expand-arrows-alt"></i>
                </a>
            </li>
        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="index.php" class="brand-link">
            <img src="dist/img/logo.png" alt="logo" class="brand-image img-circle elevation-3" style="opacity: .8">
            <span class="brand-text font-weight-light"><b>David Assignment</b></span>
        </a>
        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user panel (optional) -->

            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="image">
                    <?php
                        if(!empty($_COOKIE['photo'])){
                            echo "<img src='".$_COOKIE['photo']."' class='img-circle elevation-2' alt='User Image'>";
                        }else{
                            echo "<img src='dist/img/avatar5.png' class='img-circle elevation-2' alt='User Image'>";
                        }
                    ?>

                </div>
                <div class="info ">
                    <a href="userInfor.php?userId=<?php echo $_COOKIE['user_id']; ?>" class="d-block"><?php echo $_COOKIE['name']; ?></a>
                </div>
            </div>


            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                    <!-- Add icons to the links using the .nav-icon class
                         with font-awesome or any other icon font library -->
                    <li class="nav-item">
                        <a href="userList.php" class="nav-link">
                            <i class="nav-icon fas fa-users"></i>
                            <p>
                                User
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="nav-icon fas fa-book"></i>
                            <p>
                                Book
                                <i class="fas fa-angle-left right"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="book.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Books</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="bookType.php" class="nav-link">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Book Type</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="shelf.php" class="nav-link">
                            <i class="nav-icon fas fa-book-reader"></i>
                            <p>
                                Shelf
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="place.php" class="nav-link">
                            <i class="nav-icon fas fa-map-marker-alt"></i>
                            <p>
                                Place
                            </p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="login.php" class="nav-link">
                            <i class="nav-icon fas fa-sign-out-alt"></i>
                            <p>
                                Exit
                            </p>
                        </a>
                    </li>
                </ul>
            </nav>
            <!-- /.sidebar-menu -->
        </div>
        <!-- /.sidebar -->
    </aside>