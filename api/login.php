<?php

require_once 'Warehouse.php';

// Logout.
if ( isset( $_GET['logout']) ) {
    session_unset();
    session_destroy();
    die('{"status":200, "logout":"succeeded"}');
// login
} elseif ( isset( $_GET['username'] ) && isset( $_GET['password'] ) ) {
    // persiapan variable
    $username = $_GET['username'];
    $password = $_GET['password'];
    unset($_GET['username'], $_GET['password']);
    $row = null;

    // persiapan sql
    $sql = "SELECT * FROM `user` WHERE `username` = ?";
    if($stmt = mysqli_prepare($dbLink, $sql)) {
        // bind parameter ke sql
        mysqli_stmt_bind_param($stmt, "s", $param_username);
        // memasukan parameter
        $param_username = $username;
        // memulai ekseskusi sql
        if(mysqli_stmt_execute($stmt)){
            // mendapatkan hasil eksekusi
            $result = mysqli_stmt_get_result($stmt);
            // check apakah hasil ada
            if(mysqli_num_rows($result) == 1){
                // baca hasil
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
            } else {
                // data tidak ditemukan atau lebih dari satu row
                die('{"status":200, "login":"wrong_username"}');
            }
        }
        // tutup sql
        mysqli_stmt_close($stmt);
    } else {
        // prepare sql error
        die('{"status":200, "login":"error"}');
    }
    // tutup database
    mysqli_close($dbLink);

    // check kesesuaian password
    if ($row && $row['password'] == $password) {
        // init data ke session
        $_SESSION['u_id'] = $row['u_id'];
        $_SESSION['profile'] = $row['profile'];
        // berikan hasil ke pengirim
        echo '{"status":200, "login":"succeeded", "user_data":';
        echo '{"username":"'.$username.'", "u_id":'.$_SESSION['u_id'].', "profile":"'.$_SESSION['profile'].'"}}';
    } else {
        // password salah
        die('{"status":200, "login":"wrong_password"}');
    }
} else {
    die('{"status":200, "error":"parameter"}');
}