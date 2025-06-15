<?php
    session_start();
    include 'config.php';
    $outgoing_id = $_SESSION['user_id'];
    $searchOn = mysqli_real_escape_string($conn,$_POST['searchOn']);

    $sql = "SELECT * FROM user_form where not user_id = {$outgoing_id} 
    and(name like '%{$searchOn}%' or email like '%{$searchOn}%')";
    $query = mysqli_query($conn,$sql);
    $output = "";
    if(mysqli_num_rows($query) == 0){
        $output .= "No users are available to chat";
    }else if(mysqli_num_rows($query) > 0){
        include 'user_data.php';
        
    }
    echo $output
?>