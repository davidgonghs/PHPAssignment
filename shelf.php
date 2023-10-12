
<?php include 'header.inc.php' ?>
<!-- Content Wrapper. Contains page content -->
<!-- Content Header (Page header) -->
<div class="content-wrapper">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Shelf</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="shelf.php">Shelf</a></li>
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


            <?php include 'shelfLIst.inc.php' ?>

        </div>
        <!-- /.card -->



    </section>
</div>

<script src="tools/common.js"></script>


<?php include 'footer.inc.php' ?>

