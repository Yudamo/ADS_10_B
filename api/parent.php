<?php

require_once 'Warehouse.php';

if ( !isset($_SESSION['profile']) || $_SESSION['profile'] != 'parent' ) {
    echo '{"status":200, "error":"no_privilege"}';
    exit;
} elseif ( !isset($_GET['do']) ) {
    echo '{"status":200, "error":"unknown_api"}';
    exit;
}

$do = $_GET['do'];
unset($_GET['do']);

if ($do == 'meeting_request') {

    if ( !isset($_GET['subject']) &&
         !isset($_GET['message']) &&
         !isset($_GET['date'])
    ) {
        echo '{"status":200, "error":"parameter"}';
        exit;
    }

    // write request ke db
    // kirim notifikasi ke guru

    echo '{"status":200, "do":"succeeded"}';
} else {
    echo '{"status":200, "error":"unknown_api"}';
    exit;
}