<?php

require_once 'Warehouse.php';

if ( !isset($_SESSION['profile']) || $_SESSION['profile'] != 'teacher' ) {
    die('{"status":200, "error":"no_privilege"}');
} elseif ( !isset($_GET['do']) ) {
    die('{"status":200, "error":"unknown_api"}');
}

$do = $_GET['do'];
unset($_GET['do']);

if ($do == 'check_meeting_request') {
    // prepare sql
    $requestList = "[";
    $sql = "SELECT * FROM `meeting_request` WHERE `teacher_u_id` = ".$_SESSION['u_id']." AND `status` = 'waiting' ";
    if($result = mysqli_query($dbLink, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if ($requestList != "[") $requestList .= ", ";

                $request = "{";
                $request .= '"request_id":"'.$row["request_id"].'", ';
                $request .= '"parent_u_id":"'.$row["parent_u_id"].'", ';
                $request .= '"teacher_u_id":"'.$row["teacher_u_id"].'", ';
                $request .= '"subject":"'.$row["subject"].'", ';
                $request .= '"message":"'.$row["message"].'", ';
                $request .= '"date":"'.$row["date"].'", ';
                $request .= '"status":"'.$row["status"].'"';
                $request .= "}";

                $requestList .= $request;
            }
        }
    }
    $requestList .= ']';
    echo '{"status":200, "do":"succeeded", "requestList":'.$requestList.'}';
} else {
    die('{"status":200, "error":"unknown_api"}');
}

// write accept ke db
// TODO: kirim notifikasi ke orangtua