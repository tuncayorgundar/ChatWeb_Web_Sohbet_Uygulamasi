<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: loginPage.html"); // Giriş yapılmamışsa giriş sayfasına yönlendir
    exit;
}

echo "Hoş geldiniz, " . $_SESSION["first_name"] . " " . $_SESSION["last_name"] . "!";
echo "<br><a href='logout.php'>Çıkış Yap</a>";
?>