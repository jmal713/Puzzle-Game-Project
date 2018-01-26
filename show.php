<?php
//start session
session_start();

//Include database connection details 
require_once("db_configuration.php");
//array to store errors
$errmsg_arr= array();

//validation error flag
$errfalag = false;

 //function to clean values to avoid sql injection
 function clean($str){
     $str= @trim($str);
     if(get_magic_quotes_gpc()){
         $str= stripcslashes($str);
     }
     return mysql_real_escape_string($str);
 }
//clean the POST values 
$username = clean($_POST['username']);
$password = clean($_POST['password']);
// echo "username: " .$username; 
// echo "password " .$password;
//Input validations
if($username == ''){
    $errmsg_arr[] = 'username missing';
    $errfalag = true;
}
if($password == ''){
    $errmsg_arr[] = 'password missing';
    $errfalag = true;
}
// if there are input avalidations, redirect back to the login form
if($errfalag ){
    $_SESSION['ERRMSG_ARR'] = $errmsg_arr;
    session_write_close();
    header("location: login.php");
    exit();
}
// Create database object
$db = new Database();

// Establish connection
$db->connect();

//create query
$qry = "SELECT `display_name` FROM `registered_users` WHERE `user_email`='".$username. "' AND `password` = ENCRYPT('".$password."', `password`)";
//echo "Query: " .$qry . "<br />";
$result = mysqli_query($db->connection, $qry);

//check whether the query was successful or not
if($result){
    if(mysqli_num_rows($result) > 0){
        //login successful
        session_regenerate_id();
        $member = mysqli_fetch_assoc($result);
        // $_SESSION['SESS_MEMBER_ID']=
       // $_SESSION['ID']= $member['id'];
        $_SESSION['display_name']= $member['display_name'];
        session_write_close();
        header('location: admin.php');
        exit();

    }else{
        //login failed
        $error = 'username and password not found';
        $errfalag = true;
        if($errfalag){
            $_SESSION['ERRMSG_ARR']= $errmsg_arr;
            session_write_close();
            header('location: login.php?message='. $error);
            exit();
        }
    }

}else{
    die('query failed');
}

?>