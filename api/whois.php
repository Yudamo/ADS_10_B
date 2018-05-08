<?php

require_once 'Warehouse.php';

if ( !isset($_SESSION['profile']) ) {
    //die('{"status":200, "error":"no_privilege"}');
} elseif ( !isset($_GET['do']) ) {
    die('{"status":200, "error":"unknown_api"}');
}

$do = $_GET['do'];
unset($_GET['do']);

if ($do == "whois_u_id") {
    if (!isset($_GET['u_id'])) {
        die('{"status":200, "error":"parameter"}');
    }

    $sql = "SELECT * FROM `user` WHERE `u_id` = ".$_GET['u_id'];
    if($stmt = mysqli_prepare($dbLink, $sql)){
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                echo '{"status":200, "whois": { "name": "'.$row['username'].'", "profile":"'.$row['profile'].'" }}';
            } else{
                die('{"status":200, "whoisError":"username_not_found"}');
            }
        } else{
            die('{"status":200, "error":"database_error"}');
        }
        // tutup sql
        mysqli_stmt_close($stmt);
    } else {
        die('{"status":200, "error":"database_error"}');
    }
    // tutup database
    mysqli_close($dbLink);
} elseif ($do == 'me') {
    $sql = "SELECT * FROM `user` WHERE `u_id` = ".$_SESSION['u_id'];
    if($stmt = mysqli_prepare($dbLink, $sql)){
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);
            if(mysqli_num_rows($result) == 1){
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                echo '{"status":200, "whois": { "name": "'.$row['username'].'", "profile":"'.$row['profile'].'" }}';
            } else{
                die('{"status":200, "whoisError":"username_not_found"}');
            }
        } else{
            die('{"status":200, "error":"database_error"}');
        }
        // tutup sql
        mysqli_stmt_close($stmt);
    } else {
        die('{"status":200, "error":"database_error"}');
    }
    // tutup database
    mysqli_close($dbLink);
} elseif ($do == 'my_relation') {
// prepare sql
    $relationList = "[";

    $prof = ($_SESSION['profile'] == 'parent') ? "parent_u_id" : "teacher_u_id";
    $sql = "SELECT * FROM `relation` WHERE `".$prof."` = ".$_SESSION['u_id'];
    if ($result = mysqli_query($dbLink, $sql)) {
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                if ($relationList != "[") $relationList .= ", ";

                $relation = "{";
                $relation .= '"relation_id":"' . $row["relation_id"] . '", ';
                $relation .= '"parent_u_id":"' . $row["parent_u_id"] . '", ';
                $relation .= '"teacher_u_id":"' . $row["teacher_u_id"] . '"';
                $relation .= "}";

                $relationList .= $relation;
            }
        }
    }
    $relationList .= ']';
    echo '{"status":200, "do":"succeeded", "relationList":' . $relationList . '}';
} else {
    die('{"status":200, "error":"unknown_api"}');
}