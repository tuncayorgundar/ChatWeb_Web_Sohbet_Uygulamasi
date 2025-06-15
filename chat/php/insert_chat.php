<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        include "config.php";
        $outgoing_id = $_SESSION['user_id'];
        $incoming_id = mysqli_real_escape_string($conn,$_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn,$_POST['message']);

        if(!empty($message)){
            $insert_msg = mysqli_query($conn, 
            "INSERT INTO `messages`(`outgoing_msg_id`, `incoming_msg_id`, `msg`) 
            VALUES ('{$outgoing_id}','$incoming_id','$message')");

            if($insert_msg)
            {
                $msg_id = mysqli_insert_id($conn);
                $current_date = date('Y-m-d H:i:s');
                $insert_msg_details = mysqli_query($conn,
                "INSERT INTO `message_details`(`message_id`, `sent_date`, `sent_id`, `receive_id`) 
                VALUES ('{$msg_id}','$current_date','$outgoing_id','$incoming_id')");
            }
        }
        if(isset($_FILES['send_image'])){
            $send_image = $_FILES['send_image']['name']; //user image name
            $send_image_size = $_FILES['send_image']['size']; // user image size
            $send_image_tmp_name = $_FILES['send_image']['tmp_name'];
            $image_rename =time().$send_image;
            $image_folder = '../uploaded_img/'.$image_rename;

           

            if( move_uploaded_file($send_image_tmp_name, $image_folder)){// moving image file
                $insert_msg_img = mysqli_query($conn, 
                "INSERT INTO `messages`(`outgoing_msg_id`, `incoming_msg_id`, `msg_img`) 
                VALUES ('{$outgoing_id}','$incoming_id','$image_rename')");

                if($insert_msg_img)
                {
                    $msg_img_id = mysqli_insert_id($conn);
                    $current_date = date('Y-m-d H:i:s');
                    $insert_msg_details = mysqli_query($conn,
                    "INSERT INTO `message_details`(`message_id`, `sent_date`, `sent_id`, `receive_id`) 
                    VALUES ('{$msg_img_id}','$current_date','$outgoing_id','$incoming_id')");
                }

                
            } 
            
        }
    }else{
        header('location: login.php');
    }


?>