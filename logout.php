<?php
session_start(); // to check if we are using the same session.

require 'includes/php/helper.php'; //require PHP funciton ~ maybe comment this out.

if(!isset($_SESSION['ID'])){

    $_SESSION=  array(); // clear all session variables.

    session_destroy();// destroy the session.
    // setcookie('PHPSESSID' ", time()-3600, '/', ", 0 ,0) - destroy cookie ~maybe uncomment 
}

header("location: login.php"); // redirect the page for logining in.
exit();

?>