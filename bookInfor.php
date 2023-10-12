<?php include 'header.inc.php' ?>
<?php

$book = new Book();
$data = array();
$disabled = "";
$book_title = "";
$author = "";
$book_img = "";
$isbn_number = "";
$price = "";
$detail = "";
$place_id = "";
$shelf_id = "";
$capacity = "";
$bookId = "";
if(isset($_GET['bookId'])){
    $bookId = $_GET['bookId'];
    $book->setBookId($bookId);
    $condition="b.book_type_id = bt.type_id and b.place_id = p.place_id and b.shelf_id = s.shelf_id and b.create_user_id = u.user_id and b.book_id= '$bookId'";
    $db_name = "books b,place p,shelf s,`user` u,book_type bt";
    $column = "b.book_id,b.author,b.book_title,b.book_img,b.book_type_id,bt.type_name,b.isbn_number,b.detail,b.price,p.place_name,p.place_id,s.shelf_name,s.shelf_id,u.photo,b.create_user_id";
    $result = $db->select_one($db_name,$column,$condition);
    if(!empty($result)){
        if ($result->num_rows > 0) {
            // output data of each row
            while($row = $result->fetch_object()) {
//                $data = $row;
                $data = array("book_id"=>($row->book_id),
                    "author"=>($row->author),
                    "book_title"=>($row->book_title),
                    "book_img"=>($row->book_img),
                    "book_type_id"=>($row->book_type_id),
                    "type_name"=>($row->type_name),
                    "isbn_number"=>($row->isbn_number),
                    "price"=>($row->price),
                    "detail"=>($row->detail),
                    "shelf_name"=>($row->shelf_name),
                    "shelf_id"=>($row->shelf_id),
                    "place_name"=>($row->place_name),
                    "place_id"=>($row->place_id),
                    "photo"=>($row->photo),
                    "create_user_id"=>($row->create_user_id));
            }

        }
    }

    if($_COOKIE['user_id'] != $data['create_user_id']){
        $disabled = "disabled";
    }


}

if (isset($_POST['book_edit_submit'])) {

    if (!empty($_POST['book_title'])) {
        $book_title = $_POST['book_title'];
        if (!preg_match("/^[a-zA-Z ]*$/", $book_title)) {
            $errInfor = "Only letters and spaces are allowed in the book title. ";

        } else {
            $book->setBookTitle($book_title);
        }
    }

    if (!empty($_POST['author'])) {
        $author = $_POST['author'];
        if (!preg_match("/^[a-zA-Z ]*$/", $author)) {
            $errInfor = "Author must be a author name. ";
        } else {
            $book->setAuthor($author);
        }
    }

    if (!empty($_POST['isbn'])) {
        $isbn_number = $_POST['isbn'];
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $isbn_number)) {
            $errInfor = "ISBN Number must be a ISBN Number. ";
        } else {
            $book->setIsbnNumber($isbn_number);
        }
    }

    if (!empty($_POST['price'])) {
        $price = $_POST['price'];
        if (!preg_match("/^[0-9 ]*$/", $price)) {
            $errInfor = "Price must be a number. ";
        } else {
            $book->setPrice($price);
        }
    }

    if (!empty($_POST['detail'])) {
        $book->setDetail($_POST['detail']);
    }


    if (!empty($_POST['img_url'])) {
        $book->setBookImg($_POST['img_url']);
    }

    if (!empty($_POST['select_type_content'])) {
        $book->setBookTypeId($_POST['select_type_content']);
    }


    if ($_POST['select_place_content'] != "") {
        $book->setPlaceId($_POST['select_place_content']);
    }

    if ($_POST['select_shelf_content'] != "") {
        $book->setShelfId($_POST['select_shelf_content']);
    }


    if ($errInfor == "") {
        $sql = "book_id = '".$_POST['book_id']."'";
        $result = $db->update($book::$db_name, $book,$sql);
        if($result >= 0){
            // $page = $_SERVER['PHP_SELF'];
            $common->refreshPage("?bookId=".$_POST['book_id']);
        }else{
            $errInfor="Update Fail";
        }
    }
}

if (isset($_POST['book_submit'])) {

    if (empty($_POST['book_title'])) {
        $errInfor = "Book Title is empty";
    } else {
        $book_title = $_POST['book_title'];
        if (!preg_match("/^[a-zA-Z ]*$/", $book_title)) {
            $errInfor = "Only letters and spaces are allowed in the book title. ";

        } else {
            $book->setBookTitle($book_title);
        }
    }

    if (empty($_POST['author'])) {
        $errInfor = "Author is empty";
    } else {
        $author = $_POST['author'];
        if (!preg_match("/^[a-zA-Z ]*$/", $author)) {
            $errInfor = "Author must be a author name. ";
        } else {
            $book->setAuthor($author);
        }
    }

    if (empty($_POST['isbn'])) {
        $errInfor = "ISBN Number is empty";
    } else {
        $isbn_number = $_POST['isbn'];
        if (!preg_match("/^[a-zA-Z0-9 ]*$/", $isbn_number)) {
            $errInfor = "ISBN Number must be a ISBN Number. ";
        } else {
            $book->setIsbnNumber($isbn_number);
        }
    }

    if (empty($_POST['price'])) {
        $errInfor = "Price is empty";
    } else {
        $price = $_POST['price'];
        if (!preg_match("/^[0-9 ]*$/", $price)) {
            $errInfor = "Price must be a number. ";
        } else {
            $book->setPrice($price);
        }
    }

    if (!empty($_POST['detail'])) {
        $book->setDetail($_POST['detail']);
    }


    if (!empty($_POST['img_url'])) {
        $book->setBookImg($_POST['img_url']);
    }

    if (empty($_POST['select_type_content'])) {
        $errInfor = "Book Type is empty";
    } else {
        $book->setBookTypeId($_POST['select_type_content']);
    }


    if (empty($_POST['select_place_content'])) {
        $errInfor = "place is empty";
    } else {
        $book->setPlaceId($_POST['select_place_content']);
    }

    if (empty($_POST['select_shelf_content'])) {
        $errInfor = "shelf is empty";
    } else {
        $book->setShelfId($_POST['select_shelf_content']);
    }


    if ($errInfor == "") {
        $book->setBookId(uniqid());
        $book->setCreateUserId($_COOKIE['user_id']);
        $result = $db->insert($book::$db_name, $book);
        if ($result >= 0) {
            $common->refreshPage("?bookId=".$book->getBookId());
        } else {
            $errInfor = "ADD Fail";
        }
    }
}





?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Book Information</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="book.php">Book</a></li>
                        <li class="breadcrumb-item"><a href="bookInfor.php">Book Information</a></li>
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
         <input type="hidden" id="book_id" name="book_id" class="form-control" value="{$data['book_id']}">
Eof;
                                echo $html;
                                $html="";
                            }
                            ?>

                            <div class="form-group avatar-view " title="Change the avatar" data-toggle="modal"
                                 data-target="#avatar-modal">
                                <?php
                                $url = "dist/img/photo3.jpg";

                                if(!empty($data)){
                                    $url=$data['book_img'];
                                }
$html = <<<Eof
<img src="{$url}" alt="Avatar" id="avatar" >
<input type="hidden" id="img_url" name="img_url" value="{$url}"/>
Eof;
                                echo $html;
                                $html="";
                                ?>
                            </div>
                            <div class="form-group">
                                <label for="bookTitle">Book Title</label>
                                <input type="text" id="book_title" name="book_title"  value="<?php if(!empty($data)){ echo $data['book_title']; } ?>" class="form-control"   <?php echo  $disabled ?> >

                            </div>
                            <div class="form-group">
                                <label for="author">Author</label>
                                <input type="text" id="author" name="author"  value="<?php if(!empty($data)){ echo $data['author']; } ?>" class="form-control"   <?php echo  $disabled ?> >
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
                                <label for="isbn">ISBN Number</label>
                                <input type="text" id="isbn" name="isbn"  value="<?php if(!empty($data)){ echo $data['isbn_number']; } ?>" class="form-control"   <?php echo  $disabled ?> >
                            </div>
                            <div class="form-group">
                                <label for="price">Price</label>
                                <input type="text" id="price" name="price"  value="<?php if(!empty($data)){ echo $data['price']; } ?>" class="form-control"   <?php echo  $disabled ?> >
                            </div>
                            <div class="form-group">
                                <label for="detail">Introduction</label>
                                <textarea id="detail" name="detail" class="form-control" rows="5"   <?php echo  $disabled ?> ><?php if(!empty($data)){ echo $data['detail']; } ?></textarea>
                            </div>
                            <div class="form-group">
                                <label>Book Type</label>
                                <select class="form-control" id="bookType" name="bookType" onchange="setData('select_type_content',this.options[this.selectedIndex].value)"   <?php echo  $disabled ?> >
                                    <?php
                                    $db_name = "book_type";
                                    $column = "type_id,type_name";
                                    $result = $db->select_more($db_name, $column);
                                    $selected = false;
                                    foreach ($result as $bookType) {
                                        if(!empty($data)){
                                            if($bookType["type_id"] == $data["book_type_id"]){
$html = <<<A
<option value='{$bookType["type_id"]}' selected>{$bookType["type_name"]}</option>
A;
                                            }else{
                                                $html = <<<A
<option value='{$bookType["type_id"]}' >{$bookType["type_name"]}</option>
A;
                                            }
                                        }else{
                                            $html = <<<A
<option value='{$bookType["type_id"]}' >{$bookType["type_name"]}</option>
A;
                                        }
                                        echo $html;
                                    }
                                    $html="";
                                    ?>

                                </select>
                                <input type="hidden" id="select_type_content" name="select_type_content"/>
                            </div>

                            <div class="form-group">
                                <label>Place</label>
                                <select class="form-control" id="place" name="place"
                                        onchange="setDataAndShowShelfs(this.options[this.selectedIndex].value)"   <?php echo  $disabled ?> >
                                    <?php
                                        $db_name = "place";
                                        $column = "place_id,place_name";
                                        $result = $db->select_more($db_name, $column);
                                        $ros = array();
                                        foreach ($result as $place) {
                                            $db_name = "shelf s";
                                            $column = "s.shelf_id,s.shelf_name";
                                            $condition = "s.place_id = '" . $place["place_id"] . "'";
                                            $resultShelf = $db->select_more($db_name, $column, $condition);
                                            $shelfArray = array();
                                            foreach ($resultShelf as $shelf) {
                                                array_push($shelfArray, array($shelf["shelf_id"] => $shelf["shelf_name"]));
                                            }
                                            array_push($ros, array($place["place_id"] => $shelfArray));

                                         if(!empty($data)){
                                              if($place["place_id"] == $data["place_id"]){
$html = <<<A
<option value='{$place["place_id"]}' id='{$place["place_id"]}' selected>{$place["place_name"]}</option>
A;
                                              }else{
                                                  $html = <<<A
<option value='{$place["place_id"]}' id='{$place["place_id"]}' >{$place["place_name"]}</option>
A;
                                              }
                                         }else{
                                             if($place["place_id"] == "1"){
                                                 $html = <<<A
<option value='{$place["place_id"]}' id='{$place["place_id"]}' selected>{$place["place_name"]}</option>
A;
                                             }else{
                                                 $html = <<<A
<option value='{$place["place_id"]}' id='{$place["place_id"]}' >{$place["place_name"]}</option>
A;
                                             }

                                         }
                                            echo $html;
                                        }
                                    $html="";



                                    ?>
                                </select>
                                <input type="hidden" id="select_place_content" name="select_place_content" />
                            </div>

                            <div class="form-group">
                                <label>Shelf</label>
                                <select class="form-control" id="shelf" name="shelf" onchange="setData('select_shelf_content',this.options[this.selectedIndex].value)"  <?php echo  $disabled ?>>
                                    <?php
                                        $value = "1";
                                        $name = "Heap";
                                        if(!empty($data)){
                                            $value = $data["shelf_id"];
                                            $name = $data["shelf_name"];
                                        }
$html = <<<Eof
<option value='{$value}' id="{$value}" selected>{$name}</option>
Eof;
                                echo $html;
                                    $html="";
                                    ?>
                                </select>
                                <input type="hidden" id="select_shelf_content"  name="select_shelf_content"/>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <div class="card-footer">
                            <a href="book.php" class="btn btn-secondary">Cancel</a>
                            <?php
                            if(!empty($data)){
                                if($_COOKIE['user_id'] == $data['create_user_id']){
                                    echo "<button type='submit' name='book_edit_submit' value='book_edit_submit' class='btn btn-primary float-right'>Save</button>";
                                }
                            }else{
                                echo"<button type='submit' name='book_submit' value='book_submit' class='btn btn-success float-right'>Submit</button>";
                            }
                            ?>

                        </div>
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </form>

    </section>
    <!-- /.content -->


    <!-- Cropping modal -->
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



<script type="text/javascript">
    function setDataAndShowShelfs(id) {
        document.getElementById("place").value = id;
        document.getElementById("select_place_content").value=id;

        document.getElementById("shelf").options.length = 0;
        var json = <?php print json_encode($ros); ?>;
        for (var place in json) {
            for(element in json[place]){
                if(element == id){
                    if(json[place][element].length != 0){
                        for(shelf in json[place][element]){
                            var i=0;
                            for(key in json[place][element][shelf]){
                                if(i==0) {
                                    document.getElementById("select_shelf_content").value = key;
                                    i++;
                                }
                                var obj = document.getElementById('shelf');
                                obj.options.add(new Option(json[place][element][shelf][key],key));
                            }
                        }
                    }else{
                        var obj = document.getElementById('shelf');
                        obj.options.add(new Option("Heap", "1"));
                        document.getElementById("select_shelf_content").value = "1";
                    }
                }
            }
        }
    }
</script>

<!-- /.content-wrapper -->
<?php include 'footer.inc.php' ?>
<script src="plugins/img/cropper.min.js"></script>
<script src="plugins/img/main.js"></script>
