<?php
    require '../client/back-end/clsConnection.php';

    session_start();
    if(isset($_SESSION['admin_id']) == ''){
        if(isset($_SESSION['user_id']) == ''){
            header("Location: http://localhost/VotingSys/", TRUE, 301);
            exit();
        }
    }
   

    if(isset($_POST['action']) == 'Logout'){
        session_destroy();
        header("Location: http://localhost/VotingSys/", TRUE, 301);
        exit();
    }
?>

<html>
    <head>
        <link rel="stylesheet" href="../output.css?v=<?php echo time(); ?>">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js" integrity="sha512-TW5s0IT/IppJtu76UbysrBH9Hy/5X41OTAbQuffZFU6lQ1rdcLHzpU5BzVvr/YFykoiMYZVWlr/PX1mDcfM9Qg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
        <script src="../script.js?v=<?php echo time(); ?>"></script>
        <!-- JavaScript -->
        <script src="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/alertify.min.js"></script>

        <!-- CSS -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.min.css"/>
        <!-- Default theme -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.min.css"/>
        <!-- Semantic UI theme -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.min.css"/>
        <!-- Bootstrap theme -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.min.css"/>

        <!-- 
            RTL version
        -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/alertify.rtl.min.css"/>
        <!-- Default theme -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/default.rtl.min.css"/>
        <!-- Semantic UI theme -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/semantic.rtl.min.css"/>
        <!-- Bootstrap theme -->
        <link rel="stylesheet" href="//cdn.jsdelivr.net/npm/alertifyjs@1.13.1/build/css/themes/bootstrap.rtl.min.css"/>

        <title>Voting System</title>
    </head>
    <body>
        <div class="navbar">
            <div class="nav-logo">
                <h2 style="color:white">Voting System</h2>
            </div>
            <div class="nav-info">
                <div class="user-info">
                    <!-- <h5>Current User : Sample User</h5> -->
                </div>
                <div class="nav-action">
                    <form id="logout_form" method="POST">
                        <input type="button" name="action" value="Logout" onclick="logout()" style="background-color: #FF4d4d;!important;">
                        <input type="text" hidden name="action" value="Logout" >
                    </form>
                </div>                
            </div>
        </div>
        <div class="content" style="background-color: white;">
            <?php 
                require "../components/sidebar.php"
            ?>
            <div class="content-container">

<script>
    function logout(){
        let logout_form = document.getElementById('logout_form');
        logout_form.submit()
    }
</script>
            