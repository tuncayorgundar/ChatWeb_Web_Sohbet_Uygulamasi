<?php
session_start();
session_destroy(); // Oturumu sonlandır
header("Location: loginPage.html"); // Giriş sayfasına yönlendir
exit;
?>