<div class="card-header">
    <h3 class="card-title">Book Type List</h3>
    <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 100%;">
            <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search Book Type Name">
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
                Book Type Name
            </th>
            <th style="width: 30%">
                Book Numbers
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
        if(isset($_REQUEST['bookType_page'])){
            $pageNumber = $_REQUEST['bookType_page'];
            $startNo = ($pageNumber-1) * 10;
        }
        if(isset($_REQUEST['search'])){
            $search = $_REQUEST['search'];
            $condition = "type_name like '%$search%'";
        }
        if(isset($_REQUEST['search'])){
            $search = $_REQUEST['search'];
            $condition = "type_name like '%$search%'";
        }

        $bookType = new BookType();
        $pageCount = $db->count($bookType::$db_name,$condition);
        $bookTypePage = new Page($pageCount,"",true,"bookType_");
        $limit = " limit $startNo,10";
        if($condition != ""){
            $condition="b.create_user_id = u.user_id and ".$condition;
        }else{
            $condition="b.create_user_id = u.user_id ";
        }

        $db_name = "book_type as b , `user` as u";
        $column = "b.type_id,b.type_name,b.introduction,b.create_user_id,u.photo";

        $result = $db->select_more($db_name,$column,$condition,$limit);
        $number = $startNo;
        foreach ($result as $data){
            $number++;
            $book_number = $db->count("books","book_type_id='".$data['type_id']."'");

            $html=<<<A
                     <tr class="text-center align-items-center justify-content-center">
                        <td>
                            {$number}
                        </td>
                        <td >
                            {$data['type_name']}
                        </td>
                        <td>
                           {$book_number}
                        </td>
                         <td>
                            <img alt="Photo"  class="img-circle img-size-32" src="{$data['photo']}" >
                        </td>
                        <td class="project-actions text-right">
                               <a class="btn btn-primary btn-sm" href="bookTypInfor.php?typeId={$data['type_id']}">
                                <i class="fas fa-folder"></i> 
                        View 
                            </a>
A;
            if($_COOKIE['user_id'] == $data['create_user_id']){
                $html.=<<<A
                           <a class="btn btn-warning btn-sm" href="bookTypInfor.php?typeId={$data['type_id']}">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#bookTypeDeleteModal" onclick="setData('type_id','{$data['type_id']}')">
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
    echo $bookTypePage->fpage();
    ?>
    <a data-toggle="modal" data-target="#bookTypeAddModal" class="btn btn-success float-right">
        <i class="fas fa-plus"></i>
        Add item
    </a>
</div>

<?php

if(isset($_POST['book_type_submit'])){
    $bookTypeName = "";
    $introduction = "";
    $bookType = new BookType();
    if(empty($_POST['bookTypeName'])){
        $errInfor = "Name is empty";
    }else{
        $bookTypeName = $_POST['bookTypeName'];
        if (!preg_match("/^[a-zA-Z ]*$/",$bookTypeName)) {
            $errInfor = "Only letters and spaces are allowed in the book type name. ";

        }else{
            $bookType->setTypeName($bookTypeName);
        }
    }
    if(!empty($_POST['introduction'])){
        $introduction = $_POST['introduction'];
        $bookType->setIntroduction($introduction);
    }

    if($errInfor == ""){
        $bookType->setTypeId(uniqid());
        $bookType->setCreateUserId($_COOKIE['user_id']);
        $result = $db->insert($bookType::$db_name,$bookType);
        if($result >= 0){
            $common->refreshPage();
        }else{
            $errInfor="ADD Fail";
        }
    }
}
?>

<div class="modal fade" id="bookTypeAddModal" tabindex="-1" role="dialog" aria-labelledby="bookTypeAddModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card modal-content card-deck">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="modal-header card-header">
                    <h5 class="modal-title" id="bookTypeAddModalLabel">Book Type Information Register</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-body">

                    <div class="form-group">
                        <label for="bookTypeName">Book Type Name</label>
                        <input type="text" id="bookTypeName" name="bookTypeName" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="introduction">Introduction</label>
                        <textarea id="introduction" name="introduction" class="form-control" rows="4"></textarea>
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                    <button type="submit" name="book_type_submit" value="book_type_submit" class="btn btn-primary float-right">Submit</button>

                </div>
            </form>
        </div>
    </div>
</div>


<?php
if(isset($_POST['book_type_delete'])){
    $bookType = new BookType();
    $bookType->setTypeId($_POST['type_id']);

    $sql = "book_type_id = '".$_POST['type_id']."'";
    $book = new Book();
    $book->setBookTypeId($bookType::$Nothing);
    $result = $db->update($book::$db_name,$book,$sql);
    if($result <= 0){
        $errInfor="Delete Fail";
    }

    $result = $db->delete($bookType::$db_name,$bookType);
    if($result >= 0){
        $common->refreshPage();
    }else{
        $errInfor="Delete Fail";
    }
}
?>
<div class="modal fade" id="bookTypeDeleteModal" tabindex="-1" role="dialog" aria-labelledby="bookTypeDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card modal-content card-deck">
            <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
                <div class="modal-header card-header">
                    <h5 class="modal-title" id="bookTypeUpdateModalLabel">Remind</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-body">
                    <div class="form-group">
                        <h5>Are you sure you want to delete the current book type? <br>
                            If you want to delete the current book type, then the books under this book type will all be classified into the Nothing category.</h5>
                        <input type="hidden" id="type_id" name="type_id" class="form-control" value="">
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" name="book_type_delete" value="book_type_delete" class="btn btn-primary float-right">YES</button>
                </div>
            </form>
        </div>
    </div>
</div>
