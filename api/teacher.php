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
    $sql = "SELECT * FROM `meeting_request` WHERE `teacher_u_id` = " . $_SESSION['u_id'] . " AND `status` = 'waiting' ";
    if ($result = mysqli_query($dbLink, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if ($requestList != "[") $requestList .= ", ";

                $request = "{";
                $request .= '"request_id":"' . $row["request_id"] . '", ';
                $request .= '"parent_u_id":"' . $row["parent_u_id"] . '", ';
                $request .= '"teacher_u_id":"' . $row["teacher_u_id"] . '", ';
                $request .= '"subject":"' . $row["subject"] . '", ';
                $request .= '"message":"' . $row["message"] . '", ';
                $request .= '"date":"' . $row["date"] . '", ';
                $request .= '"status":"' . $row["status"] . '"';
                $request .= "}";

                $requestList .= $request;
            }
        }
    }
    $requestList .= ']';
    echo '{"status":200, "do":"succeeded", "requestList":' . $requestList . '}';
} elseif ($do == 'respond_meeting_request') {
    // check request parameter
    if ( !isset($_GET['meeting_request_id']) &&
         !isset($_GET['location']) &&
         !isset($_GET['time']) &&
         !isset($_GET['note']) &&
         !isset($_GET['status'])
    ) {
        die('{"status":200, "error":"parameter"}');
    }

    // write penerimaan meeting ke db
    // persiapan sql
    $sql = "UPDATE `meeting_request` SET `status` = ? WHERE `request_id` = ? ";
    if ($stmt = mysqli_prepare($dbLink, $sql)) {
        // bind parameter ke sql
        mysqli_stmt_bind_param($stmt, "si", $param_status, $param_request_id);
        // memasukan parameter
        $param_status = $_GET['status'];
        $param_request_id = $_GET['meeting_request_id'];
        // memulai ekseskusi sql
        if (!mysqli_stmt_execute($stmt)) {
            die('{"status":200, "meeting_request":"database_error"}');
        }
        // tutup sql
        mysqli_stmt_close($stmt);
    } else {
        // prepare sql error
        die('{"status":200, "meeting_request":"database_error"}');
    }

    if ($_GET['status'] == 'accept') {
        // write penerimaan meeting ke db
        // persiapan sql
        $sql = "INSERT INTO `meeting` (`meeting_request_id`,`location`,`time`,`note`,`status`)";
        $sql = $sql."VALUES (?, ?, ?, ?, ?)";
        if($stmt = mysqli_prepare($dbLink, $sql)) {
            // bind parameter ke sql
            mysqli_stmt_bind_param($stmt, "issss",
                $param_meeting_request_id, $param_location, $param_time, $param_note, $param_status);
            // memasukan parameter
            $param_meeting_request_id = $_GET['meeting_request_id'];
            $param_location = $_GET['location'];
            $param_time = $_GET['time'];
            $param_note  = $_GET['note'];
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
    }
    // tutup database
    mysqli_close($dbLink);

    // TODO: kirim notifikasi ke guru

    echo '{"status":200, "meeting_request":"succeeded"}';
} elseif ($do=="check_meeting") {
    // prepare sql
    $meetingList = "[";
    $sql = "SELECT * FROM `meeting` WHERE `meeting_request_id` IN ";
    $sql .= "(SELECT `request_id` FROM `meeting_request` WHERE `teacher_u_id` = ".$_SESSION['u_id'].")";
    if ($result = mysqli_query($dbLink, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if ($meetingList != "[") $requestList .= ", ";

                $meeting = "{";
                $meeting .= '"meeting_id":"' . $row["meeting_id"] . '", ';
                $meeting .= '"meeting_request_id":"' . $row["meeting_request_id"] . '", ';
                $meeting .= '"location":"' . $row["location"] . '", ';
                $meeting .= '"time":"' . $row["time"] . '", ';
                $meeting .= '"note":"' . $row["note"] . '", ';
                $meeting .= '"status":"' . $row["status"] . '"';
                $meeting .= "}";

                $meetingList .= $meeting;
            }
        }
    }
    $meetingList .= ']';
    echo '{"status":200, "do":"succeeded", "meetingList":' . $meetingList . '}';


} elseif ($do == "send_chat") {
    // check request parameter
    if (!isset($_GET['parent_u_id']) && !isset($_GET['message'])) {
        die('{"status":200, "error":"parameter"}');
    }

    // write request ke db
    // persiapan sql
    $sql = "INSERT INTO `chat` (`parent_u_id`,`teacher_u_id`,`sender`,`message`)";
    $sql = $sql . "VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($dbLink, $sql)) {
        // bind parameter ke sql
        mysqli_stmt_bind_param($stmt, "iiis", $param_parent_u_id, $param_teacher_u_id, $param_sender, $param_message);
        // memasukan parameter
        $param_parent_u_id = $_GET['parent_u_id'];
        $param_teacher_u_id = $_SESSION['u_id'];
        $param_sender = $_SESSION['u_id'];
        $param_message = $_GET['message'];
        // memulai ekseskusi sql
        if (!mysqli_stmt_execute($stmt)) {
            die('{"status":200, "meeting_request":"database_error", "debug":"error excecute"}');
        }
        // tutup sql
        mysqli_stmt_close($stmt);
    } else {
        // prepare sql error
        die('{"status":200, "send_chat":"database_error", "debug":"error prepare"}');
    }
    // tutup database
    mysqli_close($dbLink);

    echo '{"status":200, "send_chat":"succeeded"}';
} elseif ($do == "read_chat") {
    if (!isset($_GET['parent_u_id'])) {
        die('{"status":200, "error":"parameter"}');
    }
    // prepare sql
    $chatList = "[";
    $sql = "SELECT * FROM `chat` WHERE `parent_u_id` = ".$_GET['parent_u_id']." AND `teacher_u_id` = ".$_SESSION['u_id'];
    if (isset($_GET['last_id'])) $sql .= " AND `chat_id` > ".$_GET['last_id'];
    if ($result = mysqli_query($dbLink, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                $sender = ($row["sender"] == $_SESSION['u_id']) ? 'me' : 'you';

                if ($chatList != "[") $chatList .= ", ";

                $chat = "{";
                $chat .= '"chat_id":"' . $row["chat_id"] . '", ';
                $chat .= '"parent_u_id":"' . $row["parent_u_id"] . '", ';
                $chat .= '"teacher_u_id":"' . $row["teacher_u_id"] . '", ';
                $chat .= '"sender":"' . $sender . '", ';
                $chat .= '"message":"' . $row["message"] . '", ';
                $chat .= '"time":"' . $row["time"] . '"';
                $chat .= "}";

                $chatList .= $chat;
            }
        }
    }
    $chatList .= ']';
    echo '{"status":200, "do":"succeeded", "chatList":' . $chatList . '}';
} else {
    die('{"status":200, "error":"unknown_api"}');
}

// write accept ke db
// TODO: kirim notifikasi ke orangtua