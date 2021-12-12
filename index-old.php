<?php
session_start();

if (empty(@$_SESSION['user'])) {
  header("location: login.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>

  <title>Dasbor Rescue Tracker</title>
  <link rel="stylesheet" href="leaflet/leaflet.css" />
  <link rel="stylesheet" href="js/daterangepicker/daterangepicker.css">
  <script src="leaflet/leaflet.js"></script>

  <script src="js/jquery/jquery.min.js"></script>


    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">

    <style type="text/css">
      #mapid {
        margin: 0 auto 0 auto;
        height: 500px;
        width: 1100px;
      }
      html, body {
            height: 100%;
          }
    </style>

</head>

<body id="page-top">



    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-text">TRACKER </div>
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

            <!-- Main Content -->
            <div id="content">
                <br/>
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
                                            <option value = ""> Semua </option>
                                            <option value = "pendaki1"> Pendaki 1 </option>
                                            <option value = "pendaki2"> Pendaki 2 </option>
                                          </select>
                                        </div>

                                        <div class="col-xs-3">
                                          Rentang Waktu :
                                          <input type="text"  name='tglwaktu' class="form-control" id="reservationtime">
                                        </div>
                                        <div class="col-xs-3">
                                          <br/>
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
                                    <div id="mapid" ></div>
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
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <script src="vendor/chart.js/Chart.min.js"></script>

    <!-- Page level custom scripts -->
    <script src="js/demo/chart-area-demo.js"></script>
    <script src="js/demo/chart-pie-demo.js"></script>

    <script src="js/moment/moment.min.js"></script>
    <script src="js/daterangepicker/daterangepicker.js"></script>


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

    <script type="text/javascript">
    		var mapOptions = {
    		   center: [-7.4302745,109.1994039],
    		   zoom: 13
    		}

    		var map = new L.map('mapid', mapOptions);

    		var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');
    		map.addLayer(layer);

    		<?php
          // Sesuaikan dengan konfigurasi koneksi Anda

          $sWhere = '';
          if (!empty(@$_GET['dev'])) {
            $dev = @$_GET['dev'];
            $sWhere = " where dev_id = '$dev'";
          }


          if (!empty(@$_GET['tglwaktu'])) {
            $waktutgl = @$_GET['tglwaktu'];

            $expl = explode("-",$waktutgl);

            $expl1 = explode("/",$expl[0]);
            $atgl = trim($expl1[2])."-".$expl1[1]."-".$expl1[0];

            $expl2 = explode("/",$expl[1]);
            $btgl = $expl2[2]."-".$expl2[1]."-".trim($expl2[0]);

            if (!empty($sWhere)) {
              $sWhere = $sWhere." AND (date(time) BETWEEN '$atgl' AND '$btgl')";
            } else {
              $sWhere = "where (date(time) BETWEEN '$atgl' AND '$btgl')";
            }

          }

    			$host 	  = "localhost";
    			$username = "phpmyadmin";
    			$password = "Rescuetrack123";
    			$Dbname   = "db_pendaki_track";
    			$db 	  = new mysqli($host,$username,$password,$Dbname);

    			//$query = $db->query("SELECT * FROM geo_track");
    			$query = $db->query("SELECT * FROM ittp_pendaki_record $sWhere");
    			while ($row = $query->fetch_assoc()) {
    		?>

        pendaki='<?php echo $row['dev_id'] ?>';

        if (pendaki == 'pendaki2') {
          ico = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-green.png';
        } else if (pendaki == 'pendaki1') {
          ico = 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-black.png';
        }

        var LIcon = new L.Icon({
          iconUrl: ico,
          iconSize: [25, 41],
          iconAnchor: [12, 41],
          popupAnchor: [1, -34],
          shadowSize: [41, 41]
        });

        //L.marker([51.5, -0.09], {icon: greenIcon}).addTo(map);

    			var popUp ='<?php echo $row['dev_id'].'<br/>'.$row['hardware_serial'].'<br/>'.$row['time']; ?>';

    			L.marker([<?php echo $row["latitude"]; ?>,<?php echo $row["longitude"]; ?>], {icon: LIcon}).addTo(map).bindPopup(popUp);


    					//var marker = L.marker([-6.990325715679011,110.42296171188356]).addTo(map);
    					//marker.bindPopup('<b>Lapangan "Pancasila" Simpang Lima</b><br>Jl. Simpang Lima, Pleburan, Semarang Sel., Kota Semarang, Jawa Tengah 50241.');
    		<?php
    		}
    		?>


    	</script>

</body>

</html>
