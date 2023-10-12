<?php include 'header.inc.php' ?>
<?php
$shelfId = "";
$shelf = new Shelf();
$create_user_id = "";
$inventory = 0;
$disabled = "";
$data = array();
if(isset($_GET['shelfId'])){
    $shelfId = $_GET['shelfId'];
    $shelf->setShelfId($shelfId);
    $db_name = "shelf as s,place as p";
    $condition = "s.place_id = p.place_id and s.shelf_id = '".$shelfId."'";
    $column = "s.shelf_id,s.place_id,p.place_name,s.shelf_name,s.capacity,s.create_user_id";
    $result = $db->select_one($db_name,$column,$condition);
    if(!empty($result)){
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_object()) {
                $data = array("shelf_id"=>($row->shelf_id),
                    "place_id"=>($row->place_id),
                    "place_name"=>($row->place_name),
                    "shelf_name"=>($row->shelf_name),
                    "capacity"=>($row->capacity),
                    "create_user_id"=>($row->create_user_id));
                $create_user_id = $row->create_user_id;
            }
        }
    }

    $inventory = $db->count("books","shelf_id='".$shelfId."'");

    if($_COOKIE['user_id'] != $create_user_id){
        $disabled = "disabled";
    }
}

if(isset($_POST['shelf_submit'])){
    $shelfName = "";
    $capacity = 0;
    $shelf = new Shelf();
    if(!empty($_POST['shelfName'])){
        $shelfName = $_POST['shelfName'];
        if (!preg_match("/^[a-zA-Z ]*$/",$shelfName)) {
            $errInfor = "Only letters and spaces are allowed in the shelf name. ";

        }else{
            $shelf->setShelfName($shelfName);
        }
    }
    if(!empty($_POST['capacity'])){
        $capacity = $_POST['capacity'];
        if (!preg_match("/^[0-9 ]*$/",$capacity)) {
            $errInfor = "Capacity must be a number. ";
        }else{
            $shelf->setCapacity($capacity);
        }
    }

    if($_POST['select_content'] != ""){
        $shelf->setPlaceId($_POST['select_content']);
    }

    if($errInfor == ""){
        $sql = "shelf_id = '".$_POST['shelf_id']."'";
        $result = $db->update($shelf::$db_name,$shelf,$sql);
        if($result >= 0){
            if($_POST['select_content'] != ""){
                $book = new Book();
                $book->setPlaceId($shelf->getPlaceId());
                $result = $db->update($book::$db_name,$book,$sql);
                if($result <= 0){
                    $errInfor="Update Fail";
                }
            }
            // $page = $_SERVER['PHP_SELF'];
            $common->refreshPage("?shelfId=".$_POST['shelf_id']);
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
                    <h1 class="m-0">Shelf Information</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="shelf.php">Shelf</a></li>
                        <li class="breadcrumb-item"><a href="#">Shelf Information</a></li>
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
                                    <h3 class="card-title">Shelf Information</h3>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="shelf_id" name="shelf_id" class="form-control" value="<?php echo $shelfId ?>">
                                    <div class="form-group">
                                        <label for="bookTypeName">Shelf Name</label>
                                        <input type="text" id="shelfName" name="shelfName" class="form-control" value="<?php echo $data['shelf_name'] ?>" <?php echo  $disabled ?>>
                                    </div>
                                    <div class="form-group">
                                        <label for="bookTypeName">Place</label>
                                        <select class="form-control" id="place" name="place" onchange="setData('select_content',this.options[this.selectedIndex].id)" <?php echo  $disabled ?>>
                                        <?php
                                        $db_name = "place";
                                        $column = "place_id,place_name";
                                        $result = $db->select_more($db_name,$column);
                                        foreach ($result as $place){
                                            if($place["place_id"] == $data["place_id"]){
$html=<<<A
<option id='{$place["place_id"]}'  value='{$place["place_id"]}' selected>{$place["place_name"]}</option>
A;
                                            }else{
$html=<<<A
<option id='{$place["place_id"]}'  value='{$place["place_id"]}'>{$place["place_name"]}</option>
A;
                                            }

                                            echo $html;
                                            $html="";

                                        }
                                        ?>

                                        </select>
                                        <input type="hidden" id="select_content" name="select_content" />
                                    </div>

                                    <div class="form-group">
                                        <label for="placeShelfNumber">Inventory</label>
                                        <input id="inventory" name="inventory" class="form-control"  value="<?php echo $inventory?>"  disabled>
                                    </div>

                                    <div class="form-group">
                                        <label for="placeShelfNumber">Capacity</label>
                                        <input id="capacity" name="capacity" type="number" class="form-control"  value="<?php echo $data['capacity'] ?>"  <?php echo  $disabled ?> >
                                    </div>
                                </div>
                                <div class="card-footer">
                                    <?php
                                    if($_COOKIE['user_id'] == $create_user_id){
                                        echo "<button type='submit' name='shelf_submit' value='shelf_submit' class='btn btn-primary float-right'>Submit</button>";
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
