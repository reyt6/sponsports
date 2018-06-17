<?php session_start();
require_once "inc/tools/request.php";
require_once 'inc/tools/functions.php';
require_once 'inc/tools/database.php';
$toSend = [];
if(isset($_GET['rq'])){
    gereRequete($_GET['rq']);
    die(json_encode($toSend));
}

$auteur = "Pierre Tshiama";
$title = "SponSports";
$titre = "Bienvenue";
$imgPath = "";
$imgAlt = "logo";
$page = "index";
require_once "inc/layout/html.php";


