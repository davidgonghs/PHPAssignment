
<div class="card-header">
    <h3 class="card-title">Shelf List</h3>
    <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 100%;">
            <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search Shelf Name">
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
                Shelf Name
            </th>
            <th style="width: 20%">
                Inventory/Capacity
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
        if(isset($_REQUEST['shelf_page'])){
            $pageNumber = $_REQUEST['shelf_page'];
            $startNo = ($pageNumber-1) * 10;
        }
        if(isset($_REQUEST['search'])){
            $search = $_REQUEST['search'];
            $condition = "s.shelf_name like '%$search%'";
        }

        if(isset($_GET['placeId'])){
            if(empty($condition)){
                $condition .= " s.place_id ='".$_GET['placeId']."'";
            }else{
                $condition .= " and s.place_id ='".$_GET['placeId']."'";
            }
        }


        $shelf = new Shelf();
        $pageCount = $db->count($shelf::$db_name." s",$condition);
        $shelfPage = new Page($pageCount,"",true,"shelf_");

        if($condition != ""){
            $condition=" and ".$condition;
        }
        $tbCondition="s.create_user_id = u.user_id and s.place_id = p.place_id";
        $limit = " limit $startNo,10";
        $condition = $tbCondition.$condition;

        $db_name = "shelf as s,place as p , `user` as u";
        $column = "s.shelf_id,s.place_id,p.place_name,s.shelf_name,s.capacity,s.create_user_id,u.photo";
        $result = $db->select_more($db_name,$column,$condition,$limit);
        $number = $startNo;
        foreach ($result as $data){
            $number++;
            $inventory = $db->count("books","shelf_id='".$data['shelf_id']."'");
            $html=<<<A
                     <tr class="text-center align-items-center justify-content-center">
                        <td>
                            {$number}
                        </td>
                        <td >
                            {$data['place_name']}
                        </td>
                        <td >
                            {$data['shelf_name']}
                        </td>
                 
                        <td>
                           {$inventory}/{$data['capacity']}
                        </td>
                         <td>
                            <img alt="Photo"  class="img-circle img-size-32" src="{$data['photo']}" >
                        </td>
                        
                        <td class="project-actions text-right">
                               <a class="btn btn-primary btn-sm" href="shelfInfor.php?shelfId={$data['shelf_id']}">
                                <i class="fas fa-folder"></i> 
                        View 
                            </a>
A;
        if($_COOKIE['user_id'] == $data['create_user_id']){
$html.=<<<A
                            <a class="btn btn-warning btn-sm" href="shelfInfor.php?shelfId={$data['shelf_id']}">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#deleteModal" onclick="setData('shelf_id','{$data['shelf_id']}')">
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
    echo $shelfPage->fpage();

    ?>
    <a data-toggle="modal" data-target="#addModel" class="btn btn-success float-right">
        <i class="fas fa-plus"></i>
        Add item
    </a>
</div>
<?php

if(isset($_POST['shelf_submit'])){
    $shelfName = "";
    $capacity = 0;
    $shelf = new Shelf();
    if(empty($_POST['shelfName'])){
        $errInfor = "Name is empty";
    }else{
        $shelfName = $_POST['shelfName'];
        if (!preg_match("/^[a-zA-Z ]*$/",$shelfName)) {
            $errInfor = "Only letters and spaces are allowed in the shelf name. ";

        }else{
            $shelf->setShelfName($shelfName);
        }
    }

    if(empty($_POST['capacity'])){
        $errInfor = "capacity is empty";
    }else{
        $capacity = $_POST['capacity'];
        if (!preg_match("/^[0-9 ]*$/",$capacity)) {
            $errInfor = "Capacity must be a number. ";
        }else{
            $shelf->setCapacity($capacity);
        }
    }

    if($_POST['select_content'] == ""){
        $shelf->setPlaceId("1");
    }else{
        $shelf->setPlaceId($_POST['select_content']);
    }

    if($errInfor == ""){
        $shelf->setShelfId(uniqid());
        $shelf->setCreateUserId($_COOKIE['user_id']);
        $result = $db->insert($shelf::$db_name,$shelf);
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
                    <h5 class="modal-title" id="addModelLabel">Book Type Information Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-body">
                    <div class="form-group">
                        <label for="shelfName">Shelf Name</label>
                        <input type="text" id="shelfName" name="shelfName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="capacity">Capacity</label>
                        <input type="number" min="0" id="capacity" name="capacity" class="form-control">
                    </div>
                    <div class="form-group">
                        <label>Place</label>
                        <select class="form-control" id="place" name="place" onchange="setData('select_content',this.options[this.selectedIndex].id)">
                            <?php
                            $db_name = "place";
                            $column = "place_id,place_name";
                            $result = $db->select_more($db_name,$column);
                            foreach ($result as $data){
                                $html=<<<A
<option id='{$data["place_id"]}'>{$data["place_name"]}</option>
A;
                                echo $html;
                            }
                            ?>

                        </select>
                        <input type="hidden" id="select_content" name="select_content" />
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" name="shelf_submit" value="shelf_submit" class="btn btn-primary float-right">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>



<?php
if(isset($_POST['shelf_delete'])){
    $shelf = new Shelf();
    $shelf->setShelfId($_POST['shelf_id']);

    $sql = "shelf_id = '".$_POST['shelf_id']."'";
    $book = new Book();
    $book->setShelfId($shelf::$Heap);
    $result = $db->update($book::$db_name,$book,$sql);
    if($result <= 0){
        $errInfor="Delete Fail";
    }

    $result = $db->delete($shelf::$db_name,$shelf);

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
                        <h5>Are you sure you want to delete the current Shelf?After deleting the current bookshelf, the book information in the bookshelf will be classified into the Heap bookshelf.</h5>
                        <input type="hidden" id="shelf_id" name="shelf_id" class="form-control" value="">
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" name="shelf_delete" value="shelf_delete" class="btn btn-primary float-right">YES</button>
                </div>
            </form>
        </div>
    </div>
</div>