<?php
require_once '../api/Warehouse.php';
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
} elseif (isset($_SESSION['u_id'])) {
    header("Location: /dashboard");
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Login Student Report</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="a.png"/>
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="fonts/iconic/css/material-design-iconic-font.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100 p-t-85 p-b-20">
				<form class="login100-form validate-form" id="form-login">
					<span class="login100-form-title p-b-70">
						Selamat Datang di Situs Laporan Perkembangan Akademik Siswa
					</span>
					<span class="login100-form-avatar">
						<img src="icon.png" alt="AVATAR">
					</span>

					<div class="wrap-input100 validate-input m-t-85 m-b-35" data-validate = "Enter username">
						<input class="input100" type="text" name="username" id="username">
						<span class="focus-input100" data-placeholder="Username"></span>
					</div>

					<div class="wrap-input100 validate-input m-b-50" data-validate="Enter password">
						<input class="input100" type="password" name="pass" id="password">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

					<div class="container-login100-form-btn">
						<button class="login100-form-btn" id="btn-login">
							Masuk
						</button>
					</div>
                    <ul class="login-more p-t-30">
                    </ul>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>

	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
	<script src="vendor/animsition/js/animsition.min.js"></script>
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
	<script src="vendor/select2/select2.min.js"></script>
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
	<script src="vendor/countdowntime/countdowntime.js"></script>
	<script src="js/main.js"></script>

    <script>
        var btnLogin = $("#btn-login");

        $("#form-login").submit(function(e){
            btnLogin.css("background-color", "#333333");
            btnLogin.prop('disabled', true);

            var username = $("#username");
            var password = $("#password");

            e.preventDefault();
            $.get( "/api/login.php",
                { username: username.val(), password: password.val() }, function(data) {

                var result = JSON.parse(data);
                if (result.login === "wrong_username") {
                    alert("username atau password salah");

                    username.val("");
                    password.val("");

                    username.focus();
                } else if (result.login === "succeeded") {
                    window.location = "/dashboard";
                } else {
                    username.val("");
                    password.val("");

                    username.focus();
                }
            });

            btnLogin.prop('disabled', false);
            btnLogin.css("background-color", "#57b846");
        });
    </script>

</body>
</html>