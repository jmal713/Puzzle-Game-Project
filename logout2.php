<?php
session_start(); // to check if we are using the same session.
session_destroy();// destroy the session.
header('location: login.php'); // redirect the page for logining in.
exit();

?>