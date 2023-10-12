<?php include 'header.inc.php' ?>
<?php

$user = new User();
$userId = "";
$data = array();
$disabled = "";
if(isset($_GET['userId'])){
    $userId = $_GET['userId'];
    $user->setUserId($userId);
    $condition="user_id = '$userId'";
    $db_name = "`user`";
    $result = $db->select_one($user::$db_name,"",$condition);
    if(!empty($result)){
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_object()) {
                $data = array("user_id"=>($row->user_id),
                    "name"=>($row->name),
                    "photo"=>($row->photo),
                    "age"=>($row->age),
                    "email"=>($row->email),
                    "phone"=>($row->phone));
            }

        }
    }
    if($_COOKIE['user_id'] != $data['user_id']){
        $disabled = "disabled";
    }
}

if (isset($_POST['user_edit_submit'])) {

    if (!empty($_POST['userName'])) {
        $userName = $_POST['userName'];
        if (!preg_match("/^[a-zA-Z ]*$/", $userName)) {
            $errInfor = "Only letters and spaces are allowed in the user name. ";

        } else {
            $user->setName($userName);
        }
    }

    if (!empty($_POST['img_url'])) {
        $user->setPhoto($_POST['img_url']);
    }

    /*check email*/
    if (!empty($_POST["email"])) {
        $email = $_POST["email"];
        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/",$email)) {
            $errInfor = "Illegal mailbox format. ";
        }else{
            $user->setEmail($email);

        }
    }

    if (empty($_POST["phone"])) {
        $phone = $_POST["phone"];
        if (!preg_match("/^(\+?6?01)[0-46-9]-*[0-9]{7,8}$/",$phone)) {
            $errInfor = "Illegal phone number. ";
        }else{
            $user->setPhone($phone);
        }
    }

    if (!empty($_POST['age'])) {
        $age = $_POST['age'];
        if (!preg_match("/^[0-9 ]*$/", $age)) {
            $errInfor = "Price must be a number. ";
        } else {
            $user->setAge($age);
        }
    }


    if ($errInfor == "") {
        $sql = "user_id = '".$_POST['user_id']."'";
        $result = $db->update($user::$db_name, $user,$sql);
        if($result >= 0){
            foreach ($user as $k => $v) {
                if($k != "user_id"){
                    echo "<script>document.cookie='$k=$v';</script>";
                }
            }
            $common->refreshPage("?userId=".$_POST['user_id']);
        }else{
            $errInfor="Update Fail";
        }
    }
}



?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>User Information</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="userList.php">User</a></li>
                        <li class="breadcrumb-item"><a href="#">User Information</a></li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <?php include 'promptBar.inc.php' ?>
    <!-- Main content -->
    <section class="content">
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <div class="row">
                <div class="col-md-6">
                    <div class="card card-primary ">
                        <div class="card-header">
                            <h3 class="card-title">General</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php
                            if(!empty($data)){
                                $html = <<<Eof
         <input type="hidden" id="user_id" name="user_id" class="form-control" value="{$data['user_id']}">
Eof;
                                echo $html;
                                $html="";
                            }
                            ?>

                            <div class="form-group avatar-view " title="Change the avatar" data-toggle="modal"
                                 data-target="#avatar-modal">
                                <?php
                                $url = "dist/img/avatar5.png";

                                if(!empty($data)){
                                    $url=$data['photo'];
                                }
                                $html = <<<Eof
<img src="{$url}" alt="Avatar" id="avatar" >
<input type="hidden" id="img_url" name="img_url" value="{$url}"/>
Eof;
                                echo $html;
                                $html="";
                                ?>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <div class="col-md-6">
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title">Budget</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="isbn">Name</label>
                                <input type="text" id="userName" name="userName"  value="<?php if(!empty($data)){ echo $data['name']; } ?>" class="form-control"   <?php echo  $disabled ?> >
                            </div>
                            <div class="form-group">
                                <label for="price">Email</label>
                                <input type="text" id="email" name="email"  value="<?php if(!empty($data)){ echo $data['email']; } ?>" class="form-control"   <?php echo  $disabled ?> >
                            </div>
                            <div class="form-group">
                                <label for="price">Phone</label>
                                <input type="text" id="phone" name="phone"  value="<?php if(!empty($data)){ echo $data['phone']; } ?>" class="form-control"   <?php echo  $disabled ?> >
                            </div>
                            <div class="form-group">
                                <label for="price">Age</label>
                                <input type="text" id="age" name="age"  value="<?php if(!empty($data)){ echo $data['age']; } ?>" class="form-control"   <?php echo  $disabled ?> >
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            <a href="userList.php" class="btn btn-secondary">Cancel</a>
                            <?php
                            if(!empty($data)){
                                if($_COOKIE['user_id'] == $data['user_id']){
                                    echo "<button type='submit' name='user_edit_submit' value='user_edit_submit' class='btn btn-primary float-right'>Save</button>";
                                }else{
                                    echo "<a href='email.php?userId=".$data['user_id']."' class='btn btn-success float-right'>Email to He/She</a>";
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </form>

    </section>
    <div class="container" id="crop-avatar">
        <!-- Current avatar -->
        <div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog"
             tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form class="avatar-form" action="tools/crop.php" enctype="multipart/form-data" method="post">
                        <div class="modal-header">
                            <h4 class="modal-title" id="avatar-modal-label">Change Avatar</h4>
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="avatar-body">
                                <!-- Upload image and data -->
                                <div class="avatar-upload">
                                    <input class="avatar-src" name="avatar_src" type="hidden">
                                    <input class="avatar-data" name="avatar_data" type="hidden">
                                    <label for="avatarInput">Local upload</label>
                                    <input class="avatar-input" id="avatarInput" name="avatar_file" type="file">
                                </div>

                                <!-- Crop and preview -->
                                <div class="row">
                                    <div class="col-md-9">
                                        <div class="avatar-wrapper"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="avatar-preview preview-lg"></div>
                                        <div class="avatar-preview preview-md"></div>
                                        <div class="avatar-preview preview-sm"></div>
                                    </div>
                                </div>

                                <div class="row avatar-btns">
                                    <div class="col-md-9">
                                        <div class="btn-group">
                                            <button class="btn btn-primary" data-method="rotate" data-option="-90"
                                                    type="button" title="Rotate -90 degrees">Rotate Left
                                            </button>
                                            <button class="btn btn-primary" data-method="rotate" data-option="-15"
                                                    type="button">-15deg
                                            </button>
                                            <button class="btn btn-primary" data-method="rotate" data-option="-30"
                                                    type="button">-30deg
                                            </button>
                                            <button class="btn btn-primary" data-method="rotate" data-option="-45"
                                                    type="button">-45deg
                                            </button>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-primary" data-method="rotate" data-option="90"
                                                    type="button" title="Rotate 90 degrees">Rotate Right
                                            </button>
                                            <button class="btn btn-primary" data-method="rotate" data-option="15"
                                                    type="button">15deg
                                            </button>
                                            <button class="btn btn-primary" data-method="rotate" data-option="30"
                                                    type="button">30deg
                                            </button>
                                            <button class="btn btn-primary" data-method="rotate" data-option="45"
                                                    type="button">45deg
                                            </button>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <button class="btn btn-primary btn-block avatar-save" type="submit"
                                                name="submit">Done
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal" type="button">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div><!-- /.modal -->
    </div>
</div>

<!-- /.content-wrapper -->
<?php include 'footer.inc.php' ?>
<script src="plugins/img/cropper.min.js"></script>
<script src="plugins/img/main.js"></script>