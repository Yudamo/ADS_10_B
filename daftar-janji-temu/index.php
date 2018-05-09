<?php

require_once '../api/Warehouse.php';

if (!isset($_SESSION['u_id'])) {
    header("Location: /login");
    die();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Konsultasi Orang Tua</title>
    <link rel="icon" type="image/png" href="a.png"/>
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <link href="css/janji temu.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="header">
    <a href="#default" class="logo">Logo Sekolah</a>
    <div class="header-right">
    <a id="my-name">Nama</a>
    <a><img src="notifications-button.png"></a>
  </div>
</div>
    <!-- Side navigation -->
<div class="sidenav">
    <?php
    if ($_SESSION['profile'] == 'parent') {
        ?>
        <a href="/dashboard">Dashboard</a>
        <a href="#">Perkembangan Akademik</a>
        <a href="#">Perkembangan Psikologi</a>
        <hr style="margin-left: 30px; color:white">
        <a href="/chat">Layanan Konsultasi</a>
        <a href="/daftar-janji-temu">Layanan Temu Janji</a>
        <a href="/buat-janji-temu">Buat Temu Janji baru</a>
        <hr style="margin-left: 30px; color:white">
        <a href="#">Laporkan Masalah</a>
        <a href="/login?logout">Keluar</a>
        <?php
    } else {
        ?>
        <a href="/dashboard">Dashboard</a>
        <a href="#">Perkembangan Akademik</a>
        <a href="#">Perkembangan Psikologi</a>
        <hr style="margin-left: 30px; color:white">
        <a href="/chat">Layanan Konsultasi</a>
        <a href="/daftar-janji-temu">Layanan Temu Janji</a>
        <hr style="margin-left: 30px; color:white">
        <a href="#">Laporkan Masalah</a>
        <a href="/login?logout">Keluar</a>
        <?php
    }
    ?>
</div>
<div class="main">
<table style="width: 100%;">
  <thead>
    <tr>
        <?php
        if ($_SESSION['profile'] == 'parent') {
        ?>
            <th>Nama Guru</th>
            <th>Subject</th>
            <th>Pesan</th>
            <th>Tanggal Permintaan</th>
            <th>Status</th>
        <?php
        } else {
        ?>
            <th>Nama Orang tua</th>
            <th>Subject</th>
            <th>Pesan</th>
            <th>Tanggal Permintaan</th>
            <th>Status</th>
            <th>Respon</th>
        <?php
        }
        ?>
    </tr>
  </thead>
  <tbody id="daftar-permintaan">
  </tbody>
</table>
</div>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script src="js/index.js"></script>
    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    <script>
        // inisialisasi nama
        let profile;
        $.get("/api/whois.php", {do:"me"}, function (who) {
            let res = JSON.parse(who).whois;
            $('#my-name').text(res.name);
            profile = res.profile;

            start();
        });

        let daftarPermintaan = $('#daftar-permintaan');

        function createRequesrInst(requestID, personName, subject, message, requestTime, status) {
            let tr = $('<tr></tr>');
            tr.append($('<td>'+personName+'</td>'));
            tr.append($('<td>'+subject+'</td>'));
            tr.append($('<td>'+message+'</td>'));
            tr.append($('<td>'+requestTime+'</td>'));
            tr.append($('<td>'+status+'</td>'));

            if (profile == 'teacher') {
                let accept = $('<a href="#">Terima</a>');
                let reject = $('<a href="#">Tolak</a>');

                initRequest(tr, requestID, accept, reject);

                let td = $('<td></td>');
                td.append(accept);
                td.append($('<a> </a>'));
                td.append(reject);
                tr.append(td);
            }

            return tr;
        }
        function initRequest(request, requestID, accept, reject) {
            accept.mousedown(function() {
                let respond = {
                    do: "respond_meeting_request",
                    meeting_request_id: requestID,
                    location: "sekolah",
                    time: "15:00",
                    note: "-",
                    status: "accept"
                };
                $.get("/api/teacher.php", respond, function(res) {
                    request.remove();
                });
            });
            reject.mousedown(function() {
                let respond = {
                    do: "respond_meeting_request",
                    meeting_request_id: requestID,
                    location: "",
                    time: "",
                    note: "",
                    status: "reject"
                };
                $.get("/api/teacher.php", respond, function(res) {
                    request.remove();
                });
            });
        }

        function start() {
            $.get("/api/" + profile + ".php", {do: (profile === 'parent') ? "check_request_status" : "check_meeting_request"}, function (data) {
                let requestList = JSON.parse(data).requestList;
                requestList.map(function (request) {
                    let send = {do: "whois_u_id"};
                    if (profile === 'parent')
                        send.u_id = request.teacher_u_id;
                    else
                        send.u_id = request.parent_u_id;
                    $.get("/api/whois.php", send, function (who) {
                        daftarPermintaan.append(
                            createRequesrInst(request.request_id, JSON.parse(who).whois.name, request.subject, request.message, request.date, request.status)
                        );
                    });
                })
            });
        };
    </script>

</body>
</html>