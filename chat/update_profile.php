<?php 
    include 'php/config.php';
    session_start();
    $user_id = $_SESSION['user_id'];

    if(!isset($user_id)){
        header('location: login.php');
    }

    $select = mysqli_query($conn,"SELECT * FROM user_form WHERE user_id = '$user_id'"); 
    if(mysqli_num_rows($select)>0){
        $row = mysqli_fetch_assoc($select);
    
    }

    if(isset($_POST['update_profile']))
    {
        //$old_name = $row['name'];  // istersen eski isim kontrol ekle
        $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
        $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

        $update_nm = mysqli_query($conn, "UPDATE user_form SET name = '$update_name'
                            where user_id = '$user_id'");
                            if($update_nm){
                                $alert[] = "name update succesfull!";
                            }
                if(!empty($update_email)){
                    if(filter_var($update_email, FILTER_VALIDATE_EMAIL)){
                        $update_email = mysqli_query($conn, "UPDATE user_form SET email = '$update_email' 
                            where user_id = '$user_id'");
                            
                            $alert[] = "$update_email update succesfull";

                        
                    }else{
                        //header('location: update_profile.php');
                        $alert[] = "$update_email is not a valid email";
                    }
            }

    $image = $_FILES['update_image']['name']; //user image name
    $image_size = $_FILES['update_image']['size']; // user image size
    $image_tmp_name = $_FILES['update_image']['tmp_name'];
    $image_rename = $image;
    $image_folder = 'uploaded_img/'.$image_rename;

    if(!empty($image))
    {
        if($image_size > 2000000){
            $alert[] = "image size is too large";
        }else{
            $update_img = mysqli_query($conn, "UPDATE user_form SET img = '$image'
                            where user_id = '$user_id'");
            move_uploaded_file($image_tmp_name, $image_folder); // moving image file
            header('location: update_profile.php');
        }
    }

    $main_pass = $row['password'];
    $old_pass = mysqli_real_escape_string($conn, md5($_POST['old_pass']));
    $new_pass = mysqli_real_escape_string($conn, md5($_POST['new_pass']));
    $confirm_pass = mysqli_real_escape_string($conn, md5($_POST['confirm_pass']));

    if(!empty($main_pass) || !empty($new_pass) || !empty($confirm_pass)){
        if($old_pass != $main_pass){
            $alert[] = "old password not matched!";
        }elseif($new_pass != $confirm_pass){
            $alert[] = "confirm password not matched!";
        }
        else{
            $update_pass = mysqli_query($conn, "UPDATE user_form SET password = '$confirm_pass'
                            where user_id = '$user_id'");

            $alert[] = "password update successfull!";
            // header('location: update_profile.php');
        }
    }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
    <title>Update Profile</title>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet'  href='css/style.css'>
</head>
<body>
    <div class="update-profile">
        
        <form action="" method="post" enctype="multipart/form-data">
        <img src="uploaded_img/<?php echo $row['img']?>" alt="">
        <?php
                if(isset($alert)){
                    foreach($alert as $alert){
                        echo '<div class="alert">'.$alert.'</div>';
                    }
                }
            ?>
            <div class="flex">
                <div class="inputBox">
                    <span>Kullanıcı Adı: </span>
                    <input type="text" name="update_name" value="<?php echo $row['name']?>" class="box">
                    <span>Email</span>
                    <input type="email" name="update_email" value="<?php echo $row['email']?>" class="box">
                    <span>Fotoğrafını Güncelle</span>
                    <input type="file" name="update_image" accept="image/jpg, img/jpeg, image/png" class="box">

                </div>
                <div class="inputBox">
                    <span>Eski şifre: </span>
                    <input type="password" name="old_pass"class="box">
                    <span>Yeni şifre</span>
                    <input type="password" name="new_pass" class="box">
                    <span>Şifreyi Onayla</span>
                    <input type="password" name="confirm_pass" class="box">
                </div>
            </div>
            <div class="flex btns">
                <input type="submit" value="update profile" name="update_profile" class="btn">
                <a href="home.php" class="delete-btn">Go Back</a>
            </div>
        </form>
    </div>
</body>
</html>