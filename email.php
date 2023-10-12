<?php include 'header.inc.php' ?>
<?php
$user = new User();
$userId = "";
$data = array();
$disabled = "";
$create_user_id = "";
if(isset($_GET['userId'])){
    $userId = $_GET['userId'];
    $create_user_id = $userId;
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
}

if(isset($_POST['email_sent'])){
    $email="";
    $subject = "";
    $text = "";
    /*check email*/
    if (empty($_POST["email"])) {
        $errInfor = "Email is empty";
    } else {
        $email = $_POST["email"];
        if (!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$/",$email)) {
            $email = "";
            $errInfor = "Illegal mailbox format. ";
        }
    }

    if(!empty($_POST['subject'])){
        $subject = $_POST['subject'];
    }

    if(!empty($_POST['compose-textarea'])){
        $text = $_POST['compose-textarea'];
    }

    if($errInfor == ""){
        $header = "From : ".$_COOKIE['email']."\r\n";
        mail($email,$subject,$text,$header);
        $common->refreshPage("?userId=".$_POST['user_id']);
    }
}


?>
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Email</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="#">Email</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <section class="content">
            <div class="container-fluid">
                <section class="content">
                    <?php include 'promptBar.inc.php' ?>
                    <div class="row">
                        <?php include 'userCard.inc.php' ?>
                        <div class="col-md-6">
                            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                                <div class="card card-primary card-outline">
                                    <div class="card-header">
                                        <h3 class="card-title">Compose New Message</h3>
                                    </div>
                                    <!-- /.card-header -->
                                    <div class="card-body">
                                        <input type="hidden" id="user_id" name="user_id" class="form-control" value="<?php echo $data['user_id']; ?>">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="To:" id="email" name="email" value="<?php echo $data['email'];?>">
                                        </div>
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Subject:" id="subject" name="subject">
                                        </div>
                                        <div class="form-group">
                                            <textarea id="compose-textarea" class="form-control" style="height: 300px" name="compose-textarea">

                                            </textarea>
                                        </div>
                                    </div>
                                    <!-- /.card-body -->
                                    <div class="card-footer">
                                        <div class="float-right">
                                            <button type="submit" name='email_sent' value='email_sent' class="btn btn-primary"><i class="far fa-envelope"></i> Send</button>
                                        </div>
                                    </div>
                                    <!-- /.card-footer -->
                                </div>
                            </form>
                        </div>
                    </div>
            </section>
        </div>
        </section>
    </div>

<!-- /.card -->

<?php include 'footer.inc.php' ?>
<!-- Summernote -->
<script>
    $(function () {
        //Add text editor
        $('#compose-textarea').summernote()
    })
</script>
