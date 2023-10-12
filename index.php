<?php include 'header.inc.php' ?>

<?php
$_COOKIE['user_id'];
$book_number = $db->count("books","");
$book_type_number = $db->count("book_type","");
$user_number = $db->count("user","");
$place_number = $db->count("place","");
$shelf_number = $db->count("shelf","");

$result = $db->select_more("book_type"," *,(select count(*) from books where books.book_type_id = book_type.type_id) as bt_book_number","EXISTS(select * from books where book_type.type_id=books.book_type_id) ORDER BY bt_book_number DESC limit 0,5");
$btName = array();
$btBookNumber = array();
foreach ($result as $data){
    array_push($btName,$data["type_name"]);
    array_push($btBookNumber,$data["bt_book_number"]);
}
$count = 0;
foreach ($btBookNumber as $key => $value) {
    $count=$count+$value;
}
$pieColorSource = ['#f56954', '#00a65a', '#f39c12', '#00c0ef', '#3c8dbc'];
$pieColor = array();
for ($i = 0; $i < count($btName); ++$i) {
    $pieColor[$i] = $pieColorSource[$i];
}
array_push($btName,"Other");
array_push($btBookNumber,$book_number-$count);

array_push($pieColor,'#d2d6de');

$result = $db->select_more("place"," *,(select count(*) from books where books.place_id = place.place_id) as place_book_number");
$placeName = array();
$placeBookNumber = array();
foreach ($result as $data){
    array_push($placeName,$data["place_name"]);
    array_push($placeBookNumber,$data["place_book_number"]);
}


?>
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">Home</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-primary">
                            <div class="inner">
                                <h3><?php echo $book_number;?></h3>
                                <p>Total Books</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-book"></i>
                            </div>
                            <a href="book.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3><?php echo $book_type_number;?></h3>
                                <p>Total Book Type</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-ios-bookmarks"></i>
                            </div>
                            <a href="bookType.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3><?php echo $user_number;?></h3>
                                <p>User Registrations</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person-add"></i>
                            </div>
                            <a href="userList.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3><?php echo $place_number;?></h3>

                                <p>Total Place Number</p>
                            </div>
                            <div class="icon">
                                <i class="fas fa-map-marker-alt"></i>

                            </div>
                            <a href="place.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-2 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3><?php echo $shelf_number;?></h3>
                                <p>Total Shelf Number</p>
                            </div>
                            <div class="icon">
                                <span class="iconify" data-icon="ion:file-tray-stacked" data-inline="false"></span>
                            </div>
                            <a href="shelf.php" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-7 connectedSortable">
                        <!-- TO DO List -->
                        <div class="card">

                            <!-- /.card-header -->
                            <?php include 'bookList.inc.php' ?>
                        </div>


                        <!-- /.card -->
                    </section>
                    <!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                    <section class="col-lg-5 connectedSortable">

                        <!-- /.card -->
                        <div class="card card-primary card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Type Book Pie Chart</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                            <!-- /.card-body -->
                        </div>



                        <!-- STACKED BAR CHART -->
                        <div class="card card-success card-outline">
                            <div class="card-header">
                                <h3 class="card-title">Place Book Number Bar Chart</h3>

                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="chart">
                                    <canvas id="stackedBarChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                                </div>
                            </div>
                            <!-- /.card-body -->
                        </div>
                        <!-- /.card -->

                        <!-- /.card -->
                    </section>
                    <!-- right col -->
                </div>
                <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
<?php include 'footer.inc.php' ?>

<script>
    $(function () {
        var donutData = {
            labels: <?php echo json_encode($btName); ?>,
            datasets: [
                {
                    data: <?php echo json_encode($btBookNumber); ?>,
                    backgroundColor : <?php echo json_encode($pieColor); ?>,
                }
            ]
        }

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData        = donutData;
        var pieOptions     = {
            maintainAspectRatio : false,
            responsive : true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })


        var areaChartData = {
            labels  :<?php echo json_encode($placeName); ?>,
            datasets: [
                {
                    label               : 'Book Number',
                    backgroundColor     : 'rgba(60,141,188,0.9)',
                    borderColor         : 'rgba(60,141,188,0.8)',
                    pointRadius          : true,
                    pointColor          : '#3b8bba',
                    pointStrokeColor    : 'rgba(60,141,188,1)',
                    pointHighlightFill  : '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data                : <?php echo json_encode($placeBookNumber); ?>
                }
            ]
        }

        //---------------------
        //- STACKED BAR CHART -
        //---------------------
        var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
        var stackedBarChartData = $.extend(true, {}, areaChartData)

        var stackedBarChartOptions = {
            responsive              : true,
            maintainAspectRatio     : false,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }

        new Chart(stackedBarChartCanvas, {
            type: 'bar',
            data: stackedBarChartData,
            options: stackedBarChartOptions
        })
    })
</script>


