<?php 
    include 'config.php';
    session_start();
    $user_id = $_SESSION['user_id'];

    $logout_id = mysqli_real_escape_string($conn,$_GET['logout_id']);
    if(!isset($logout_id)){
        header('location: home.php');
    }else{
        $status = "Offline Now";
        $update = mysqli_query($conn, "UPDATE user_form SET status = '$status' 
        where user_id = '$logout_id' "); // changing user status
        if($update){
            session_unset();
            session_destroy();
            header('location: ../login.php');
        }
    }
   
?>