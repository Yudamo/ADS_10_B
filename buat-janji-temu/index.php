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
    <link href="css/back.css" rel="stylesheet">
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
    </div>
<div class="main">
    <div class="">
        <div class="col-x-2">
            <div class="form-control" style="width: 25cm; height: 12.5cm; margin-left: 125px; background-color: #e6e4e4;">
                <p2>Permintaan Temu Janji</p2>
                <hr>
                <form id="form-permintaan" class="topBefore" >

		          <input id="perihal" type="text" placeholder="Perihal">
		          <textarea id="pesan" type="text" placeholder="Pesan"></textarea>
                  <input id="tanggal" type="text" placeholder="Tanggal janji (2018-06-22)">
                  <input style="color: black; cursor: pointer;" id="btnSubmit" type="submit" value="Buat Janji">

                </form>
            </div>
        </div>
    </div>
</div>
    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
    <script>
        let btnSubmit = $("#btnSubmit");
        btnSubmit.prop('disabled', true);
        btnSubmit.val('tunggu sebentar');

        $.get("/api/whois.php", {do:"me"}, function (who) {
            let res = JSON.parse(who).whois;
            $('#my-name').text(res.name);
            load();
        });

        let teacher_u_id = 0;
        function load() {
            $.get("/api/whois.php", {do: "my_relation"}, function (data) {
                let relationList = JSON.parse(data).relationList;
                teacher_u_id = relationList[0].teacher_u_id;

                btnSubmit.prop('disabled', false);
                btnSubmit.val('buat janji');
            });
        }

        $("#form-permintaan").submit(function(e){
            e.preventDefault();

            btnSubmit.prop('disabled', true);
            btnSubmit.val('tunggu sebentar');

            let subject = $('#perihal');
            let message = $('#pesan');
            let date = $('#tanggal');

            let send = {do: "meeting_request", teacher_u_id, subject: subject.val(), message: message.val(), date: date.val()};
            $.get( "/api/parent.php", send, function(data) {
                subject.val('');
                message.val('');
                date.val('');

                btnSubmit.prop('disabled', false);
                btnSubmit.val('buat janji');

                window.location = '/daftar-janji-temu';
            });
        });
    </script>
</body>
</html>