<?php

require_once 'Warehouse.php';

if ( !isset($_SESSION['profile']) || $_SESSION['profile'] != 'teacher' ) {
    echo '{"status":200, "error":"no_privilege"}';
    exit;
} elseif ( !isset($_GET['do']) ) {
    echo '{"status":200, "error":"unknown_api"}';
    exit;
}

$do = $_GET['do'];
unset($_GET['do']);

if ($do == 'meeting_request_accept') {

    if ( !isset($_GET['meeting_request_id']) ) {
        echo '{"status":200, "error":"parameter"}';
        exit;
    }

    // write accept ke db
    // kirim notifikasi ke orangtua

    echo '{"status":200, "do":"succeeded"}';
} else {
    echo '{"status":200, "error":"unknown_api"}';
    exit;
}