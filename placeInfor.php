<?php include 'header.inc.php' ?>
<?php

$placeId = "";

$place = new Place();
$create_user_id = "";
$place_shelf_number = 0;
if(isset($_GET['placeId'])){
    $placeId = $_GET['placeId'];
    $place->setPlaceId($placeId);
    $result = $db->select_one($place::$db_name,"",$place);
    if(!empty($result)){
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_object()) {
                $place->setPlaceId($row->place_id);
                $place->setPlaceName($row->place_name);
                $place->setCreateUserId($row->create_user_id);
                $create_user_id = $row->create_user_id;
            }
        }
    }

    $place_shelf_number = $db->count("shelf","place_id='".$placeId."'");
}
?>


<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Place Information</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="place.php">Place</a></li>
                        <li class="breadcrumb-item"><a href="#">Place Information</a></li>
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
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Book Type Information</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="bookTypeName">Place Name</label>
                                    <input type="text" id="placeName" name="placeName" class="form-control" value="<?php echo $place->getPlaceName() ?>" disabled>
                                </div>
                                <div class="form-group">
                                    <label for="placeShelfNumber">Place Shelf Number</label>
                                    <input id="placeShelfNumber" name="placeShelfNumber" class="form-control"  value="<?php echo $place_shelf_number ?>"  disabled>
                                </div>
                            </div>
                        </div>
                    </div>


                    <?php include 'userCard.inc.php' ?>



                    <div class="col-md-12">
                        <div class="card">
                         <?php include 'shelfLIst.inc.php' ?>
                        </div>
                    </div>



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
