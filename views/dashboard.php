<?php
defined('APLIKASI') or exit('Anda tidak dizinkan mengakses langsung script ini!');
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Dasbor Rescue Tracker</title>
    <link rel="stylesheet" href="./assets/libs/leaflet/leaflet.css" />
    <link rel="stylesheet" href="./assets/js/daterangepicker/daterangepicker.css">

    <script src="./assets/js/jquery/jquery.min.js"></script>


    <!-- Custom fonts for this template-->
    <link href="./assets/libs/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="./assets/css/sb-admin-2.min.css" rel="stylesheet">

    <style type="text/css">
        #mapid {
            margin: 0 auto 0 auto;
            height: 500px;
            width: 1100px;
        }

        html,
        body {
            height: 100%;
        }
    </style>

    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCiwYVoe4ngFTq94T6UYJQKzX0D8UzQbDY&callback=initialize"
        async defer></script>
    <script type="text/javascript">
        var marker;

        function initialize() {
            // Variabel untuk menyimpan informasi lokasi
            var infoWindow = new google.maps.InfoWindow;
            //  Variabel berisi properti tipe peta
            var mapOptions = {
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                zoom: 16
            }
            // Pembuatan peta
            var peta = new google.maps.Map(document.getElementById('mapid'), mapOptions);
            // Variabel untuk menyimpan batas kordinat
            var bounds = new google.maps.LatLngBounds();
            // Pengambilan data dari database MySQL
            <?php

            $sWhere = '';

            if (!empty(@$_GET['dev'])) {

                $dev = @$_GET['dev'];
                $sWhere = " where dev_id = '$dev'";

            }


            if (!empty(@$_GET['tglwaktu'])) {

                $waktutgl = @$_GET['tglwaktu'];
                $expl = explode("-", $waktutgl);
                $expl1 = explode("/", $expl[0]);
                $atgl = trim($expl1[2]).
                "-".$expl1[1].
                "-".$expl1[0];

                $expl2 = explode("/", $expl[1]);
                $btgl = $expl2[2].
                "-".$expl2[1].
                "-".trim($expl2[0]);

                if (!empty($sWhere)) {

                    $sWhere = $sWhere.
                    " AND (date(time) BETWEEN '$atgl' AND '$btgl')";

                } else {

                    $sWhere = "where (date(time) BETWEEN '$atgl' AND '$btgl')";

                }

            }

            if (empty($sWhere)) {

                $sWhere = "where (date(time) BETWEEN '".date('Y-m-d').
                "' AND '".date('Y-m-d').
                "')";

            }

            $query = $conn->fetchAllAssociative("SELECT * FROM ittp_pendaki_record ".$sWhere);

            foreach($query as $key => $row) {

                    $nama = $row['dev_id'].
                    '<br/>'.$row['hardware_serial'].
                    '<br/>'.$row['time'];;
                    $lat = $row["latitude"];
                    $long = $row["longitude"];
                    echo "addMarker($lat, $long, '$nama');\n";

                }

                //echo "addMarker(-7.4302745, 109.1994039, 'aaa')";
                ?>
                // Proses membuat marker
                function addMarker(lat, lng, info) {
                    var lokasi = new google.maps.LatLng(lat, lng);
                    bounds.extend(lokasi);
                    var marker = new google.maps.Marker({
                        map: peta,
                        position: lokasi
                    });
                    peta.fitBounds(bounds);
                    bindInfoWindow(marker, peta, infoWindow, info);
                }
            // Menampilkan informasi pada masing-masing marker yang diklik
            function bindInfoWindow(marker, peta, infoWindow, html) {
                google.maps.event.addListener(marker, 'click', function () {
                    infoWindow.setContent(html);
                    infoWindow.open(peta, marker);
                });
            }
        }
    </script>

</head>

<body id="page-top">



    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= $baseURL ?>">

                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-search-location"></i>
                </div>
                <div class="sidebar-brand-text" style="padding-left: 15px;">TRACKER</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="index.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Topbar -->
            <nav class="navbar navbar-expand navbar-light bg-white topbar static-top shadow">

                <!-- Sidebar Toggle (Topbar) -->
                <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                    <i class="fa fa-bars"></i>
                </button>

                <!-- Topbar Navbar -->
                <ul class="navbar-nav ml-auto">

                    <!-- Nav Item - User Information -->
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="mr-2 d-none d-lg-inline text-gray-600 small">Administrator</span>
                            <img class="img-profile rounded-circle" src="<?= $baseURL ?>assets/img/undraw_profile.svg">
                        </a>
                        <!-- Dropdown - User Information -->
                        <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                            aria-labelledby="userDropdown">
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                Logout
                            </a>
                        </div>
                    </li>

                </ul>

            </nav>
            <!-- End of Topbar -->
            <!-- Main Content -->
            <div id="content">
                <br />
                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Area Chart -->
                    <div class="col-xl-12 col-lg-7">

                        <div class="card-body">
                            <form action="">
                                <div class="form-group">
                                    <div class="col-xs-2">
                                        Device :
                                        <select name="dev" class="form-control">
                                            <option value=""> Semua </option>
                                            <option value="pendaki1"> Pendaki 1 </option>
                                            <option value="pendaki2"> Pendaki 2 </option>
                                        </select>
                                    </div>

                                    <div class="col-xs-3">
                                        Rentang Waktu :
                                        <input type="text" name='tglwaktu' class="form-control" id="reservationtime">
                                    </div>
                                    <div class="col-xs-3">
                                        <br />
                                        <input type="submit" value="Filter" class="form-control">
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>


                    <!-- Area Chart -->
                    <div class="col-xl-12 col-lg-7">
                        <!-- Card Header - Dropdown -->


                        <!-- Card Body -->
                        <div class="card-body">
                            <div id="mapid"></div>
                        </div>
                    </div>





                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->



        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>
    
    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Siap untuk keluar?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Pilih "Logout" jika kamu siap untuk mengakhiri sesi saat ini.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Batal</button>
                    <a class="btn btn-primary" href="<?= $baseURL ?>logout">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="./assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="./assets/libs/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="./assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="./assets/libs/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="./assets/js/demo/chart-area-demo.js"></script>
    <script src="./assets/js/demo/chart-pie-demo.js"></script>

    <script src="./assets/js/moment/moment.min.js"></script>
    <script src="./assets/js/daterangepicker/daterangepicker.js"></script>


    <script>
        $('#reservationtime').daterangepicker({
            timePicker: false,
            timePickerIncrement: 1,
            timePicker24Hour: true,
            locale: {
                format: 'DD/MM/YYYY'
            }
        })
    </script>


</body>

</html>