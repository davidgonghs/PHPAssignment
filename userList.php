<?php include 'header.inc.php' ?>


    <!-- Content Wrapper. Contains page content -->
    <!-- Content Header (Page header) -->
    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">User</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                            <li class="breadcrumb-item"><a href="userList.php">User</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <?php include 'promptBar.inc.php' ?>

            <!-- Default box -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">User List</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 100%;">
                            <input type="text" id="search" name="table_search" class="form-control float-right" placeholder="Search User Name">
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
                                User Name
                            </th>
                            <th style="width: 20%">
                                Email
                            </th>
                            <th style="width: 20%">
                                Phone
                            </th>
                            <th style="width: 10%">
                                Books
                            </th>
                            <th style="width: 8%">
                                Photo
                            </th>
                            <th style="width: 20%">
                            </th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        $condition = "";
                        $startNo = 0;
                        if(isset($_REQUEST['user_page'])){
                            $pageNumber = $_REQUEST['user_page'];
                            $startNo = ($pageNumber-1) * 10;
                        }
                        if(isset($_REQUEST['search'])){
                            $search = $_REQUEST['search'];
                            $condition = "name like '%$search%'";
                        }
                        $user = new User();
                        $pageCount = $db->count($user::$db_name,$condition);
                        $userePage = new Page($pageCount,"",true,"user_");

                        $limit = " limit $startNo,10";
                        $db_name = "`user`";
                        $result = $db->select_more($db_name,"*",$condition,$limit);
                        $number = $startNo;
                        foreach ($result as $data){
                            $number++;
                            $book_number = $db->count("books","create_user_id='".$data['user_id']."'");
                            $html=<<<A
                     <tr class="text-center align-items-center justify-content-center">
                        <td>
                            {$number}
                        </td>
                        <td >
                            {$data['name']}
                        </td>
                        <td>
                          {$data['email']}
                        </td>
                        <td>
                           {$data['phone']}
                        </td>
                        <td>
                           {$book_number}
                        </td>
                         <td>
                            <img alt="Photo"  class="img-circle img-size-32" src="{$data['photo']}" >
                        </td>
                        
                        <td class="project-actions text-right">
                            <a class="btn btn-primary btn-sm" href="userInfor.php?userId={$data['user_id']}">
                                <i class="fas fa-folder"></i> 
                        View 
                            </a>
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
                    echo $userePage->fpage();

                    ?>

                </div>
            </div>
            <!-- /.card -->

        </section>
    </div>



<?php include 'footer.inc.php' ?>