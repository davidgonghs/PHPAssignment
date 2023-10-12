<div class="card-header">
    <h3 class="card-title">Book List</h3>
    <div class="card-tools">
        <div class="input-group input-group-sm" style="width: 100%;">
            <input type="text" id="search" name="table_search" class="form-control float-left" placeholder="Search By Book Name">
            <div class="input-group-append">
                <button type="submit" class="btn btn-default" onclick="search()">
                    <i class="fas fa-search"></i>
                </button>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <a class="btn bg-info color-palette btn-sm" href="#" data-toggle="modal" data-target="#searchModal">
                <i class="fas fa-search"></i>
                Advanced Search
            </a>
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
            <th style="width: 10%">
                Book Title
            </th>
            <th style="width: 10%">
                Author
            </th>
            <th style="width: 10%">
                Book Type
            </th>
            <th style="width: 10%">
                Place
            </th>
            <th style="width: 10%">
                Shelf
            </th>
            <th style="width: 8%">
                Create User
            </th>
            <th style="width: 10%">
            </th>
        </tr>
        </thead>
        <tbody>
        <?php
        $condition = "";
        $startNo = 0;
        if(isset($_REQUEST['book_page'])){
            $pageNumber = $_REQUEST['book_page'];
            $startNo = ($pageNumber-1) * 10;
        }
        if(isset($_REQUEST['search'])){
            $search = $_REQUEST['search'];
            $condition .= " b.book_title like '%$search%'";
        }

        if(isset($_GET['author'])){
            $author = $_GET['author'];
            if(empty($condition)){
                $condition .= " b.author like '%$author%'";
            }else{
                $condition .= " and b.author like '%$author%'";
            }
        }

        if(isset($_GET['isbn'])){
            $isbn = $_GET['isbn'];
            if(empty($condition)){
                $condition .= " b.isbn_number like '%$isbn%'";
            }else{
                $condition .= " and b.isbn_number like '%$isbn%'";
            }
        }

        if(isset($_GET['typeId'])){
            if(empty($condition)){
                $condition .= " b.book_type_id ='".$_GET['typeId']."'";
            }else{
                $condition .= " and b.book_type_id ='".$_GET['typeId']."'";
            }
        }

        if(isset($_GET['placeId'])){
            if(empty($condition)){
                $condition .= " b.place_id ='".$_GET['placeId']."'";
            }else{
                $condition .= " and b.place_id ='".$_GET['placeId']."'";
            }
        }

        if(isset($_GET['shelfId'])){
            if(empty($condition)){
                $condition .= " b.shelf_id ='".$_GET['shelfId']."'";
            }else{
                $condition .= " and b.shelf_id ='".$_GET['shelfId']."'";
            }
        }


        $book = new Book();
        $pageCount = $db->count($book::$db_name." b",$condition);
        $bookPage = new Page($pageCount,"",true,"book_");

        if($condition != ""){
            $condition="and ".$condition;
        }
        $tbCondition="b.book_type_id = bt.type_id and b.place_id = p.place_id and b.shelf_id = s.shelf_id and b.create_user_id = u.user_id ";
        $limit = " limit $startNo,10";
        $condition = $tbCondition.$condition;
        $db_name = "books b,place p,shelf s,`user` u,book_type bt";
        $column = "b.book_id,b.author,b.book_title,b.book_img,b.book_type_id,bt.type_name,b.isbn_number,b.price,p.place_name,s.shelf_name,u.photo,b.create_user_id ";
        $result = $db->select_more($db_name,$column,$condition,$limit);
        $number = $startNo;
        foreach ($result as $data){
            $number++;
            $html=<<<A
                     <tr class="text-center align-items-center justify-content-center">
                        <td>
                            {$number}
                        </td>
                        <td >
                            {$data['book_title']}
                        </td>
                        <td >
                            {$data['author']}
                        </td>
                        <td>
                           {$data['type_name']}
                        </td>
                        <td>
                           {$data['place_name']}
                        </td>
                        <td>
                           {$data['shelf_name']}
                        </td>
                         <td>
                            <img alt="Photo"  class="img-circle img-size-32" src="{$data['photo']}" >
                        </td>
                        
                        <td class="project-actions text-right">
                               <a class="btn btn-primary btn-sm" href="bookInfor.php?bookId={$data['book_id']}">
                                <i class="fas fa-folder"></i> 
                        View 
                            </a>
A;
if($_COOKIE['user_id'] == $data['create_user_id']){
 $html.=<<<A
                            <a class="btn btn-warning btn-sm" href="bookInfor.php?bookId={$data['book_id']}">
                                <i class="fas fa-pencil-alt"></i>
                                Edit
                            </a>
                            <a class="btn btn-danger btn-sm" href="#" data-toggle="modal" data-target="#deleteModal" onclick="setData('book_id','{$data['book_id']}')">
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
    echo $bookPage->fpage();

    ?>
    <a href="bookInfor.php" class="btn btn-success float-right">
        <i class="fas fa-plus"></i>
        Add item
    </a>
</div>

<?php
if(isset($_POST['book_delete'])){
    $book = new Book();
    $book->setBookId($_POST['book_id']);
    $result = $db->delete($book::$db_name,$book);
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
                        <h5>Are you sure you want to delete the current book?</h5>
                        <input type="hidden" id="book_id" name="book_id" class="form-control" value="">
                    </div>
                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                    <button type="submit" name="book_delete" value="book_delete" class="btn btn-primary float-right">YES</button>
                </div>
            </form>
        </div>
    </div>
</div>



<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="card modal-content card-deck">
                <div class="modal-header card-header">
                    <h5 class="modal-title" id="searchModalLabel">Advanced Search</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body card-body">
                    <div class="form-group">
                        <label for="bookTitle">Book Title</label>
                        <input type="text" id="book_title" name="book_title"  class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author"  class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="isbn">ISBN Number</label>
                        <input type="text" id="isbn" name="isbn"  class="form-control" >
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
                                onchange="setDataAndShowShelfs(this.options[this.selectedIndex].value)" >
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
$html = <<<A
<option value='{$place["place_id"]}' id='{$place["place_id"]}' >{$place["place_name"]}</option>
A;

                                echo $html;
                            }
                            echo print (json_encode($ros));
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
                            $html = <<<Eof
<option value='{$value}' id="{$value}" selected>{$name}</option>
Eof;
                            echo $html;
                            $html="";
                            ?>
                        </select>
                        <input type="hidden" id="select_shelf_content"  name="select_shelf_content"/>
                    </div>

                </div>
                <div class="modal-footer card-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" name="book_search" value="book_search" class="btn btn-primary float-right"  onclick="advancedSearch()">Search</button>
                </div>
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