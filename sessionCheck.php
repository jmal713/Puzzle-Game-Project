<?php
    session_start();

    if(!isset($_SESSION['display_name']))
    {

        header('Location: login.php');
    }
?>