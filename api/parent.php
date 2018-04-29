<?php

require_once 'Warehouse.php';

if ( !isset($_SESSION['profile']) || $_SESSION['profile'] != 'parent' ) {
    die('{"status":200, "error":"no_privilege"}');
} elseif ( !isset($_GET['do']) ) {
    die('{"status":200, "error":"unknown_api"}');
}

$do = $_GET['do'];
unset($_GET['do']);

if ($do == 'meeting_request') {
    // check request parameter
    if ( !isset($_GET['teacher_u_id']) &&
         !isset($_GET['subject']) &&
         !isset($_GET['message']) &&
         !isset($_GET['date'])
    ) {
        die('{"status":200, "error":"parameter"}');
    }

    // write request ke db
    // persiapan sql
    $sql = "INSERT INTO `meeting_request` (`parent_u_id`,`teacher_u_id`,`subject`,`message`,`date`,`status`)";
    $sql = $sql."VALUES (?, ?, ?, ?, ?, ?)";
    if($stmt = mysqli_prepare($dbLink, $sql)) {
        // bind parameter ke sql
        mysqli_stmt_bind_param($stmt, "iissss", $param_parent_u_id,
            $param_teacher_u_id, $param_subject, $param_message, $param_date, $param_status);
        // memasukan parameter
        $param_parent_u_id = $_SESSION['u_id'];
        $param_teacher_u_id = $_GET['teacher_u_id'];
        $param_subject = $_GET['subject'];
        $param_message  = $_GET['message'];
        $param_date = date("Y-m-d", strtotime($_GET['date']));
        $param_status = 'waiting';
        // memulai ekseskusi sql
        if(!mysqli_stmt_execute($stmt)) {
            die('{"status":200, "meeting_request":"database_error"}');
        }
        // tutup sql
        mysqli_stmt_close($stmt);
    } else {
        // prepare sql error
        die('{"status":200, "meeting_request":"database_error"}');
    }
    // tutup database
    mysqli_close($dbLink);

    // TODO: kirim notifikasi ke guru

    echo '{"status":200, "meeting_request":"succeeded"}';
} else {
    die('{"status":200, "error":"unknown_api"}');
}