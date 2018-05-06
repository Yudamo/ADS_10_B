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
    if (!isset($_GET['teacher_u_id']) &&
        !isset($_GET['subject']) &&
        !isset($_GET['message']) &&
        !isset($_GET['date'])
    ) {
        die('{"status":200, "error":"parameter"}');
    }

    // write request ke db
    // persiapan sql
    $sql = "INSERT INTO `meeting_request` (`parent_u_id`,`teacher_u_id`,`subject`,`message`,`date`,`status`)";
    $sql = $sql . "VALUES (?, ?, ?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($dbLink, $sql)) {
        // bind parameter ke sql
        mysqli_stmt_bind_param($stmt, "iissss", $param_parent_u_id,
            $param_teacher_u_id, $param_subject, $param_message, $param_date, $param_status);
        // memasukan parameter
        $param_parent_u_id = $_SESSION['u_id'];
        $param_teacher_u_id = $_GET['teacher_u_id'];
        $param_subject = $_GET['subject'];
        $param_message = $_GET['message'];
        $param_date = date("Y-m-d", strtotime($_GET['date']));
        $param_status = 'waiting';
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
    // tutup database
    mysqli_close($dbLink);

    // TODO: kirim notifikasi ke guru

    echo '{"status":200, "meeting_request":"succeeded"}';
} elseif ($do=="check_request_status") {
    // prepare sql
    $requestList = "[";
    $sql = "SELECT * FROM `meeting_request` WHERE `parent_u_id` = " . $_SESSION['u_id'];
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
} elseif ($do=="check_meeting") {
    // prepare sql
    $meetingList = "[";
    $sql = "SELECT * FROM `meeting` WHERE `meeting_request_id` IN ";
    $sql .= "(SELECT `request_id` FROM `meeting_request` WHERE `parent_u_id` = " . $_SESSION['u_id'] . ")";
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
    if (!isset($_GET['teacher_u_id']) && !isset($_GET['message'])) {
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
        $param_parent_u_id = $_SESSION['u_id'];
        $param_teacher_u_id = $_GET['teacher_u_id'];
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
    if (!isset($_GET['teacher_u_id'])) {
        die('{"status":200, "error":"parameter"}');
    }
    // prepare sql
    $chatList = "[";
    $sql = "SELECT * FROM `chat` WHERE `parent_u_id` = ".$_SESSION['u_id']." AND `teacher_u_id` = ".$_GET['teacher_u_id'];
    if ($result = mysqli_query($dbLink, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if ($chatList != "[") $chatList .= ", ";

                $sender = ($row["sender"] == $_SESSION['u_id']) ? 'me' : 'you';

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