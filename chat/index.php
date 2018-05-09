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

    <link href="css/back.css" rel="stylesheet">
    <link href="css/reset.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">

</head>

<body>

<div class="header">
    <a href="#default" class="logo">Logo Sekolah</a>
    <div class="header-right">
        <a id="my-name">Nama Siswa</a>
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


<div class="main" style="margin-top: 75px">
    <div class="wrapper">
        <div class="container">
            <div class="left">
                <div class="top">
                    <a>Layanan Chat</a>
                </div>
                <ul class="people" id="person-list">
                </ul>
            </div>
            <div class="right" >
                <div class="top">
                    <span>To: <span class="name">Loading ...</span></span>
                </div>
                <a id="chatListV">
                </a>
                <div class="write">
                    <input type="text" id="textSend"/>
                    <a href="#" onmousedown="sendMessage()" class="write-link send"></a>
                </div>
            </div>
        </div>
    </div>
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
    // notifikasi
    document.addEventListener('DOMContentLoaded', function () {
        if (!Notification) {
            alert('Desktop notifications not available in your browser. Try Chromium.');
            return;
        }
        if (Notification.permission !== "granted")
            Notification.requestPermission();
    });
    function notifyMe() {
        if (Notification.permission !== "granted")
            Notification.requestPermission();
        else {
            var notification = new Notification('Recommendation - Chat', {
                icon: '/chat/notifications-button.png',
                body: "Ada pesan masuk baru",
            });
            notification.onclick = function () {
                window.focus();
                this.cancel();
            };

        }

    }

    // inisialisasi nama
    let profile;
    $.get("/api/whois.php", {do:"me"}, function (who) {
        let res = JSON.parse(who).whois;
        $('#my-name').text(res.name);
        profile = res.profile;
        load();
    });
    // buka semua chat
    function load() {
        $.get("/api/whois.php", {do: "my_relation"}, function (data) {
            let relationList = JSON.parse(data).relationList;
            relationList.map(function(relation) {
                if (profile === 'parent')
                    loadChat(relation.teacher_u_id);
                else
                    loadChat(relation.parent_u_id);
            })
        });
    }

    let personList = $('#person-list');
    let chatListV = $('#chatListV');
    let chatData = [];
    let selectedChat = 0;

    let textSend = $('#textSend');
    function sendMessage() {
        let send = {do:"send_chat", message: textSend.val()};
        if (profile === 'parent')
            send.teacher_u_id = selectedChat;
        else
            send.parent_u_id = selectedChat;
        $.get("/api/"+profile+".php", send, function (result) {
            updateLastChat(selectedChat, chatData[selectedChat], true);
        });
    }
    function loadChat(personId) {
        // make chat list
        let chat = createChat(personId);
        chatListV.append(chat);
        let person = createPersonInst(personId);
        personList.append(person);
        // get chat info
        $.get("/api/whois.php", {do:"whois_u_id", u_id: personId}, function (who) {
            updatePersonName(person, JSON.parse(who).whois.name);
        });
        // get chat data

        let send = {do: "read_chat"};
        if (profile === 'parent')
            send.teacher_u_id = personId;
        else
            send.parent_u_id = personId;

        $.get("/api/"+profile+".php", send, function (data) {
            // parse chat list
            let chatList = JSON.parse(data).chatList;
            for (let i = 0;i < chatList.length; i++) {
                // add chat data to chat list
                chat.append(createChatInst(chatList[i].sender, chatList[i].message));
                if (i === chatList.length-1) {
                    chatData[personId] = chatList[i].chat_id;

                    let time = new Date(chatList[i].time);
                    updatePersonLastMessage(person, time.getHours()+":"+time.getMinutes(), chatList[i].message);
                }
            }

            setInterval(() => {
                updateLastChat(personId, chatData[personId]);
            }, 3000)
        });
    }
    let updating = [];
    function updateLastChat(personId, lastChatID, clear = false) {
        if (!updating[personId]) {
            updating[personId] = true;

            let send = {do: "read_chat", last_id: lastChatID};
            if (profile === 'parent')
                send.teacher_u_id = personId;
            else
                send.parent_u_id = personId;

            $.get("/api/"+profile+".php", send, function (data) {
                // parse chat list
                let chatList = JSON.parse(data).chatList;
                for (let i = 0; i < chatList.length; i++) {
                    // add chat data to chat list
                    $('#chat-' + personId).append(createChatInst(chatList[i].sender, chatList[i].message));
                    if (i === chatList.length - 1) {
                        chatData[personId] = chatList[i].chat_id;

                        let time = new Date(chatList[i].time);
                        updatePersonLastMessage($('#person-' + personId), time.getHours() + ":" + time.getMinutes(), chatList[i].message);

                        if (chatList[i].sender !== "me") {
                            notifyMe();
                            $('#person-' + personId).mousedown();
                        }
                    }
                }
                if (clear) textSend.val("");
                updating[personId] = false;
            });
        } else if(clear) {
            if (clear) textSend.val("");
        }
    }

    //function
    function createPersonInst(personId) {
        // create person instance
        let li = $("<li class=\"person\" data-chat=\""+personId+"\" id=\"person-"+personId+"\"></li>");
        li.append($("<img src=\"person.png\" alt=\"\" />"));
        li.append($("<span class=\"name\">Loading ...</span>"));
        li.append($("<span class=\"time\">...</span>"));
        li.append($("<span class=\"preview\">...</span>"));

        initPerson(li, personId);

        return li;

    }
    function initPerson(person, personId) {
        person.mousedown(function () {
            if ($(this).hasClass('.active')) {
                return false;
            } else {
                let personName = $(this).find('.name').text();
                $(this).addClass('active');
                $('.left .person').removeClass('active');
                $('.chat').removeClass('active-chat');
                $('.right .top .name').html(personName);
                $('.chat[data-chat = ' + personId + ']').addClass('active-chat');

                selectedChat = personId;
            }
        });
    }
    function updatePersonName(person, personName) {
        person.find(".name").text(personName);
    }
    function updatePersonLastMessage(person, time, lastMessage) {
        person.find(".time").text(time);
        person.find(".preview").text(lastMessage);
    }
    function createChat(personID) {
        return $("<div class=\"chat\" data-chat=\""+personID+"\" id=\"chat-"+personID+"\">");
    }
    function createChatInst(sender, message) {
        return $("<div class=\"bubble "+sender+"\"></div>").text(message);
    }
</script>
</body>

</html>