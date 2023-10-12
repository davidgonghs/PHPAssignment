<div class="card-header">
    <h3 class="card-title">Place List</h3>
    <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 100%;">
            <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search Place Name">
            <div class="input-group-append">
                <button type="submit" class="btn btn-default" onclick="search()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card-body p-0">
    <table class="table table-hover text-nowrap">
        <thead>
        <tr class="text-center">
            <th style="width: 1%">
                #
            </th>
            <th style="width: 20%">
                Place Name
            </th>
            <th style="width: 20%">
                Place Shelf Number
            </th>
            <th style="width: 20%">
                Book Number
            </th>
            <th style="width: 8%">
                Create User
            </th>
            <th style="width: 20%">
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $condition = "";
        $startNo = 0;
        if(isset($_REQUEST['place_page'])){
            $pageNumber = $_REQUEST['place_page'];
            $startNo = ($pageNumber-1) * 10;
        }
        if(isset($_REQUEST['search'])){
            $search = $_REQUEST['search'];
            $condition = "place_name like '%$search%'";
        }
        $place = new Place();
        $pageCount = $db->count($place::$db_name,$condition);
        $placePage = new Page($pageCount,"",true,"place_");

        if($condition != ""){
            $condition=" and ".$condition;
        }
        $tbCondition="p.create_user_id = u.user_id";
        $limit = " limit $startNo,10";
        $condition = $tbCondition.$condition;

        $db_name = "place as p , `user` as u";
        $column = "p.place_id,p.place_name,p.create_user_id,u.photo";
        $result = $db->select_more($db_name,$column,$condition,$limit);
        $number = $startNo;
        foreach ($result as $data){
            $number++;
            $place_shelf_number = $db->count("shelf","place_id='".$data['place_id']."'");
            $place_book_number = $db->count("books","place_id='".$data['place_id']."'");
            $html=<<<A
                     <tr class="text-center align-items-center justify-content-center">
                        <td>
                            {$number}
                        </td>
                        <td >
                            {$data['place_name']}
                        </td>
                        <td>
                           {$place_shelf_number}
                        </td>
                        <td>
                           {$place_book_number}
                        </td>
                         <td>
                            <img alt="Photo"  class="img-circle img-size-32" src="{$data['photo']}" >
                        </td>
                        
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="placeInfor.php?placeId={$data['place_id']}">
                                <i class="fas fa-folder"></i> 
                        View 
                            </a>
A;
            if($_COOKIE['user_id'] == $data['create_user_id']){
                $html.=<<<A
                            <a class="btn btn-warning btn-sm" href="#" data-toggle="modal" data-target="#editModel" onclick="setData('update_place_id','{$data['place_id']}');setData('update_place_name','{$data['place_name']}');">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#deleteModal" onclick="setData('place_id','{$data['place_id']}')">
                                <i class="fas fa-trash"></i>
                        Delete
                            </a>
A;
            }


            $html.=<<<A
                        </td>
                    </tr>
A;

            echo $html;
        }

        ?>

        </tbody>
    </table>
</div>
<!-- /.card-body -->
<div class="card-footer clearfix">
    <?php
    echo $placePage->fpage();

    ?>
    <a data-toggle="modal" data-target="#addModel" class="btn btn-success float-right">
        <i class="fas fa-plus"></i>
        Add item
    </a>
</div>

<?php
if(isset($_POST['place_submit'])){
    $placeName = "";
    $place = new Place();
    if(empty($_POST['placeName'])){
        $errInfor = "Name is empty";
    }else{
        $placeName = $_POST['placeName'];
        if (!preg_match("/^[a-zA-Z ]*$/",$placeName)) {
            $errInfor = "Only letters and spaces are allowed in the place name. ";

        }else{
            $place->setPlaceName($placeName);
        }
    }

    if($errInfor == ""){
        $place->setPlaceId(uniqid());
        $place->setCreateUserId($_COOKIE['user_id']);
        $result = $db->insert($place::$db_name,$place);
        if($result >= 0){
            $common->refreshPage();
        }else{
            $errInfor="ADD Fail";
        }
    }
}

?>
<div class="modal fade" id="addModel" tabindex="-1" role="dialog" aria-labelledby="addModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card modal-content card-deck">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="modal-header card-header">
                    <h5 class="modal-title" id="addModelLabel">Book Type Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-body">
                    <div class="form-group">
                        <label for="inputName">Place Name</label>
                        <input type="text" id="placeName" name="placeName" class="form-control">
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="place_submit" value="place_submit" class="btn btn-primary float-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
if(isset($_POST['place_edit_submit'])){
    $place = new Place();
    $place->setPlaceName($_POST['update_place_name']);
    $result = $db->update($place::$db_name,$place," place_id='".$_POST['update_place_id']."'");
    if($result >= 0){
        $common->refreshPage();
    }else{
        $errInfor="Update Fail";
    }
}
?>
<div class="modal fade" id="editModel" tabindex="-1" role="dialog" aria-labelledby="editModelLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card modal-content card-deck">
            <form action='<?php echo $_SERVER['PHP_SELF']; ?>' method="post">
                <div class="modal-header card-header">
                    <h5 class="modal-title" id="editModelLabel">Book Type Information</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-body">
                    <input type="hidden" id="update_place_id" name="update_place_id" class="form-control" value="">
                    <div class="form-group">
                        <label for="inputName">Place Name</label>
                        <input type="text" id="update_place_name" name="update_place_name" class="form-control">
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="place_edit_submit" value="place_edit_submit" class="btn btn-primary float-right">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php
if(isset($_POST['place_delete'])){
    $place = new Place();
    $place->setPlaceId($_POST['place_id']);

    $shelf = new Shelf();
    $shelf->setPlaceId($place::$Heap);
    $sql = "place_id = '".$_POST['place_id']."'";
    $result = $db->update($shelf::$db_name,$shelf,$sql);
    if($result <= 0){
        $errInfor="Delete Fail";
    }

    $book = new Book();
    $book->setPlaceId($shelf->getPlaceId());
    $result = $db->update($book::$db_name,$book,$sql);
    if($result <= 0){
        $errInfor="Delete Fail";
    }

    $result = $db->delete($place::$db_name,$place);
    if($result >= 0){
        $common->refreshPage();
    }else{
        $errInfor="Delete Fail";
    }
}
?>
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card modal-content card-deck">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="modal-header card-header">
                    <h5 class="modal-title" id="deleteModalLabel">Remind</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-body">
                    <div class="form-group">
                        <h5>Are you sure you want to delete the current Place?If you delete the current place, the information under the place will be transferred to the heap place.</h5>
                        <input type="hidden" id="place_id" name="place_id" class="form-control" value="">
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" name="place_delete" value="place_delete" class="btn btn-primary float-right">YES</button>
                </div>
            </form>
        </div>
    </div>
</div>
