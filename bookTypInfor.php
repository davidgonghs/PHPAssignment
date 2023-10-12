<?php include 'header.inc.php' ?>
<?php
$typeId = "";
$bookType = new BookType();
$create_user_id = "";
$disabled = "";
$book_number = 0;
if(isset($_GET['typeId'])){
    $typeId = $_GET['typeId'];
    $bookType->setTypeId($typeId);
    $result = $db->select_one($bookType::$db_name,"",$bookType);
    if(!empty($result)){
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_object()) {
                $bookType->setTypeName($row->type_name);
                $bookType->setIntroduction($row->introduction);
                $bookType->setCreateUserId($row->create_user_id);
                $create_user_id = $row->create_user_id;
            }
        }
    }
    $book_number = $db->count("books","book_type_id='".$bookType->getTypeId()."'");

    if($_COOKIE['user_id'] != $bookType->getCreateUserId()){
        $disabled = "disabled";
    }
}

if(isset($_POST['book_type_submit'])){
    $bookTypeName = "";
    $introduction = "";
    $bookType2 = new BookType();
    if(!empty($_POST['bookTypeName'])){
        $bookTypeName = $_POST['bookTypeName'];
        if (!preg_match("/^[a-zA-Z ]*$/",$bookTypeName)) {
            $errInfor = "Only letters and spaces are allowed in the book type name. ";

        }else{
            $bookType2->setTypeName($bookTypeName);
        }
    }

    if(!empty($_POST['introduction'])){
        $introduction = $_POST['introduction'];
        $bookType2->setIntroduction($introduction);
    }

    if($errInfor == ""){
        $sql = "type_id = '".$_POST['type_id']."'";
        $result = $db->update($bookType::$db_name,$bookType2,$sql);
        if($result >= 0){
           // $page = $_SERVER['PHP_SELF'];
            $common->refreshPage("?typeId=".$_POST['type_id']);
        }else{
            $errInfor="Update Fail";
        }
    }
}



?>

<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Book Type</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="Book.php">Book</a></li>
                        <li class="breadcrumb-item"><a href="bookType.php">Book Type</a></li>
                        <li class="breadcrumb-item"><a href="#">Book Type Information</a></li>
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
              <div class="col-md-6">
                  <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                  <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Book Type Information</h3>
                        </div>
                        <div class="card-body">
                            <input type="hidden" id="type_id" name="type_id" class="form-control" value="<?php echo $bookType->getTypeId() ?>">
                            <div class="form-group">
                                <label for="bookTypeName">Book Type Name</label>
                                <input type="text" id="bookTypeName" name="bookTypeName" class="form-control" value="<?php echo $bookType->getTypeName() ?>"   <?php echo $disabled ?> >
                            </div>
                            <div class="form-group">
                                <label for="placeShelfNumber">Book Number</label>
                                <input id="book_number" name="book_number" class="form-control"  value="<?php echo $book_number?>"  disabled>
                            </div>

                            <div class="form-group">
                                <label for="introduction">Introduction</label>
                                <textarea id="introduction" name="introduction" class="form-control" rows="5"  <?php echo  $disabled ?> ><?php echo $bookType->getIntroduction() ?></textarea>
                            </div>
                        </div>
                        <div class="card-footer">
                        <?php
                        if($_COOKIE['user_id'] == $bookType->getCreateUserId()){
                            echo "<button type='submit' name='book_type_submit' value='book_type_submit' class='btn btn-primary float-right'>Submit</button>";
                        }
                        ?>
                        <!-- /.card-body -->
                        </div>
                     </div>
                  </form>
              </div>


                <?php include 'userCard.inc.php' ?>

                <div class="col-md-12">
                    <div class="card">
                        <?php include 'bookList.inc.php' ?>
                    </div>
                    </div>
                </div>
            </section>
        </div>
    </div>

<script src="tools/common.js"></script>
<?php include 'footer.inc.php' ?>