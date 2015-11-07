<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <script type="text/javascript" src="<?php echo asset_url().'js/javascript.js' ?>"></script>
    <link type="text/css" rel="stylesheet" href="<?php echo asset_url().'css/stylesheet.css' ?>">
    <script src="<?php echo asset_url().'jquery/jquery-2.1.3.min.js'?>"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBoM6DEn2AzxSG-nomRJJ5US4uZUgt0_gI"></script>
    <title>This is the common header</title>
</head>
<body>
<div id="page"><!--Beginning of the page-->

    <div id="header"><!--This is the beginning of the header-->


        <a id="homelogo">homelogo </a>



        <a id="klogo">Klogo</a>

        <a id="date" href="https://www.youtube.com/watch?v=EDyfUEKNDpM">
            <script type="text/javascript">
                new imageclock.displayDate();
                new imageclock.display();
            </script>
        </a>


    </div><!--This is the end of the header-->
    <div id="leftpage"><!--Here's what's on the left of the page-->

        <input type="button" id="seminar_register_menu_button" class="menuItem" value="Register &#10; Seminar"/>
        <br>

        <input type="button" id="attendee_register_menu_button" class="menuItem"  value="Register &#10; Attendee"/>
        <br>

        <input type="button" id="seminar_overview_login_menu_button" class="menuItem"  value="Seminar &#10;Overview &#10; Login"/>
        <br>

        <input type="button" id="centre_manager_menu_button" class="menuItem"  value="Centre  &#10;Management"/>


    </div><!--Here ends what's on the left of the page-->

    <div id="content"><!--Here's the content-->