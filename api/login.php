<?php

require_once 'Warehouse.php';

// Logout.
if ( isset( $_GET['logout']) ) {
    session_unset();
    session_destroy();
    echo '{"status":200, "logout":"succeeded"}';
// login
} elseif ( isset( $_GET['username'] ) && isset( $_GET['password'] ) ) {
    $username = $_GET['username'];
    unset($_GET['username']);

    $login_RET[1]['password'] = "PASSWORD"; // DATABASE QUERY

    if ($login_RET && $login_RET[1]['password'] == $_GET['password']) {
        unset($_GET['password']);

        //$_SESSION['u_id'] = $login_RET[1]['u_id'];
        $_SESSION['profile'] = 'parent'; //$login_RET[1]['profile'];

        echo '{"status":200, "login":"succeeded"}';
    } else {
        echo '{"status":200, "login":"wrong_password"}';
    }
}