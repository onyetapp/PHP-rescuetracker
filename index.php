<?php
session_start();
header("Access-Control-Allow-Origin: *");
date_default_timezone_set('Asia/Jakarta');

require_once __DIR__ . '/config/corp.php';
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/router.php';
require_once __DIR__ . '/config/helper.php';
require_once __DIR__ . '/vendor/autoload.php';

// Gunakan namespace disini
use OnyetApp\Route;
use OnyetApp\Helper;

// Connection to Datbase Every Request
$conn = \Doctrine\DBAL\DriverManager::getConnection($config['databases']);

// Base route
Route::add('/', function() {

    if (empty(@$_SESSION['user'])) {
        
        header("location: ". Helper::base_url() ."login");
    
    } else {

        header("location: ". Helper::base_url() ."dashboard");
    
    }

});

Route::add('/login', function() {

    $baseURL = Helper::base_url();

    if (empty(@$_SESSION['user'])) {
        
        include __DIR__ . '/views/login.php';
    
    } else {

        header("location: ". Helper::base_url() ."dashboard");
    
    }

});

Route::add('/dashboard', function() {

    global $conn;
    $baseURL = Helper::base_url();

    if (empty(@$_SESSION['user'])) {
        
        header("location: ". Helper::base_url() ."login");
    
    } else {

        include __DIR__ . '/views/dashboard.php';
    
    }
    
});

Route::add('/logout', function() {

    session_destroy();
    header("location: ". Helper::base_url() ."login");

});

Route::add('/login/exec', function() {

    $username = @$_POST['user'];
    $password = @$_POST['password'];

    if ($username == 'admin' && $password == 'Tracker123!') {

        $_SESSION['user'] = "admin";
        header("location: ". Helper::base_url() ."dashboard");

    } else {

        echo "<script>alert('User / Password salah!');window.history.back();</script>";
        exit();

    }

}, 'post');

Route::add('/push/request/ttn', function() {

    global $conn;
    
    $raw = file_get_contents('php://input');
    $obj = json_decode($raw);
    $loc = base64_decode($obj->payload_raw);
    $geo = array('lat' => 0, 'lng' => 0);
    // strtotime($obj->metadata->time)
    $tim = date('Y-m-d H:i:s', time());

    if ($loc) {
        
        $loc = explode(';', $loc);
        $geo['lat'] = (float) $loc[0];
        $geo['lng'] = (float) $loc[1];

    }

    $conn->executeStatement("INSERT INTO ittp_pendaki_record(app_id, dev_id, hardware_serial, counter, payload, time, frequency, data_rate, gtw_id, rssi, snr, latitude, longitude) VALUES('". $obj->app_id ."', '". $obj->dev_id ."', '". $obj->hardware_serial ."', ". $obj->counter .", '". $obj->payload_raw ."', '". $tim ."', ". $obj->metadata->frequency .", '". $obj->metadata->data_rate ."', '". $obj->metadata->gateways[0]->gtw_id ."', ". $obj->metadata->gateways[0]->rssi .", ". $obj->metadata->gateways[0]->snr .", ". $geo['lat'] .", ". $geo['lng'] .")");
    echo json_encode(array('status' => 'success'));

}, 'post');

Route::add('/api/v1/device/record', function() {

    global $conn;

    $data = $_GET;

    if (isset($data['dev_id']) && isset($data['counter']) && isset($data['latitude']) && isset($data['longitude']) && isset($data['rssi']) && isset($data['snr'])) {

        $id = $data['dev_id'];
        $loraStation = (isset($data['lora_station']) ? $data['lora_station'] : '');
        $appID = (isset($data['app_id'])) ? $data['app_id'] : 'trackingpendaki';
        $conn->executeStatement("INSERT INTO ittp_pendaki_record(app_id, dev_id, hardware_serial, counter, payload, rssi, snr, latitude, longitude, lora_station) VALUES('". $appID ."', '". $id ."', '". $id ."', ". $data['counter'] .", '". json_encode($data) ."', ". $data['rssi'] .", ". $data['snr'] .", ". $data['latitude'] .", ". $data['longitude'] .", '". $loraStation ."')");
        echo json_encode(array('status' => 'success'));

    } else {

        echo json_encode(array('status' => 'error'));
    
    }

}, 'get');

// Sediakan Halaman 404
Route::pathNotFound(function() {
    echo json_encode(array(
        'status'    => 'error',
        'error'     => '404',
        'message'   => 'Hi, did you lost ?'
    ));
});

Route::methodNotAllowed(function() {
    echo json_encode(array(
        'status'    => 'error',
        'error'     => '401',
        'message'   => 'Someone told me to get back!'
    ));
});

// Run the router
Route::run(BASEPATH);