<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/index.css" media="all">
    <link rel="icon" type="image/x-icon" href="image/icon.png">
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.0/dist/trix.css">
    <script type="text/javascript" src="https://unpkg.com/trix@2.0.0/dist/trix.umd.min.js"></script>
    <title>SCP Database</title>
</head>

<?php
    //session
    session_name("main_session");
    session_start();
    //connexion SQL
    $database = new PDO('mysql:host=localhost; dbname=wiki_scp; charset=utf8', 'root', '');
    $_SESSION["src_img"]="image/";