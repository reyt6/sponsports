<?php if ( count( get_included_files() ) == 1) die( '--access denied--' ); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>
        <?php echo $title; ?>
    </title>
    <!-- Map script -->
    <script async defer src="https://maps.google.com/maps/api/js?key=AIzaSyALDpjfBI4-FBNwAVAp65Rxucfg4b_ichg"></script>

    <!-- JQuery -->
    <script src="js/jquery-3.3.1.min.js"></script>


    <!-- Personal JavaScript -->
    <script src="js/index.js"></script>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Plugin JavaScript -->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Bootstrap core CSS -->
    <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom fonts for this template -->
    <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href='https://fonts.googleapis.com/css?family=Kaushan+Script' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
    <link href='https://fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700' rel='stylesheet' type='text/css'>

    <!-- Custom styles for this template -->
    <link href="css/agency.min.css" rel="stylesheet">
    <link href="css/index.css" rel="stylesheet">
</head>
<body>
<div id="global">
        <nav class="navbar navbar-expand-lg navbar-dark fixed-top" id="mainNav">
            <div class="container">
                <a class="navbar-brand brandTitle js-scroll-trigger" href="index.php"><?php echo $title ?></a>
                <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="fa fa-bars"></i>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav text-uppercase ml-auto">
                        <li class="nav-item"><a class="nav-link" id="introLink" href="#intro">Introduction</a></li>
                        <li class="nav-item"><a class="nav-link" id="servicesLink" href="#services">Services</a></li>
                        <li class="nav-item"><a class="nav-link" id="rechercheLink" href="#recherche">Recherche</a></li>
                        <li class="nav-item" id="createPartnership"><a class="nav-link" href="createPartnership.html">Proposer un projet</a></li>
                        <li class="nav-item" id="profile"><a class='nav-link' href='registration.html'>Inscription</a></li>
                        <li class="nav-item" id="message"><a class="nav-link" href="message.html">Messages</a></li>
                        <li class="nav-item" id="login"><a class="nav-link" href="login.html">Connexion</a></li>
                    </ul>
            </div>
        </nav>
        <div id="content">
            <?php require_once 'main.php'; ?>
        </div>
    <footer class="fixed-bottom" id="copyright">
        Copyright © Sponsports 2018
    </footer>
</div>
</body>
</html>

