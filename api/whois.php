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
                echo '{"status":200, "whois":"'.$row['username'].'"}';
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
} else {
    die('{"status":200, "error":"unknown_api"}');
}