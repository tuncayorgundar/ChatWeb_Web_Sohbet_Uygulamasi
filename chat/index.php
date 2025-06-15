<?php
    include 'php/config.php'; // including the database connection
    session_start();
    $image_rename = 'default-avatar.pnp'; // avatar default image
    if(isset($_POST['submit'])){ // if user click the submit btn
        $ran_id = rand(time(),1000000000);
        $name = mysqli_real_escape_string($conn, $_POST['name']);
        $email = mysqli_real_escape_string($conn, $_POST['email']);
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
        //$name = mysqli_real_escape_string($conn, $_POST['name']);
        //declaring input

        if(filter_var($email, FILTER_VALIDATE_EMAIL)){ // checking if email is valid
            $image = $_FILES['image']['name']; //user image name
            $image_size = $_FILES['image']['size']; // user image size
            $image_tmp_name = $_FILES['image']['tmp_name'];
            $image_rename .= $image;
            $image_folder = 'uploaded_img/'.$image_rename;
            $status ='Offline Now'; //user status

            $select = mysqli_query($conn,"SELECT * FROM user_form WHERE email = '$email'
                        AND password = '$password'"); // checking if user already exist
            if(mysqli_num_rows($select)>0){
                $alert[] = "user already exist";
            }else{
                if($password != $password){
                    $alert[] = "Password not matched!";
                }elseif($image_size > 2000000){
                    $alert[] = "image size is too large";
                }else{
                    $insert = mysqli_query($conn, "INSERT INTO `user_form`(`user_id`,`name`, `email`, `password`, `img`, `status`) 
                    VALUES ('$ran_id','$name','$email','$password','$image_rename','$status')");

                    if($insert){

                        $current_date = date('Y-m-d H:i:s');
                        $insert_detail = mysqli_query($conn, "INSERT INTO `user_details`(`user_id`,`register_date`,`name`) 
                        VALUES ('$ran_id','$current_date','$name')");

                        move_uploaded_file($image_tmp_name, $image_folder); // moving image file
                        header('location: login.php');
                    }else{
                        $alert[] = "connection failed please retry!";

                    }
                }
                
            }
        }else{
            $alert[] ="$email is not a valid email!";
        }

    }
    if(isset( $_SESSION['user_id'])){
        header("location: home.php");
    }


?>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>create account</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='css/style.css'>
</head>
<body>
    <div class="form-container">
        <form action="" method="post" enctype="multipart/form-data">
            <h3>create account</h3>
            <?php
                if(isset($alert)){
                    foreach($alert as $alert){
                        echo '<div class="alert">'.$alert.'</div>';
                    }
                }
            ?>
            <!-- <div class="alert">error please try again</div> -->
            <input type="text" name="name" placeholder="Enter username" class="box" required>
            <input type="email" name="email" placeholder="Enter email" class="box" required>
            <input type="password" name="password" placeholder="Enter password" class="box" required>
            <input type="password" name="cpassword" placeholder="Confirm password" class="box" required>
            <input type="file" name="image"class="box" accept="image/*">
            <input type="submit" name="submit" class="btn" value="Start Chatting">
            <p>Already have an account? <a href="login.php">Login now</a></p>
            
        </form>
    </div>
</body>
</html>