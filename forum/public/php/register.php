<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["kullanici_adi"];
    $password = password_hash($_POST["sifre"], PASSWORD_DEFAULT);
    $first_name = $_POST["ad"];
    $last_name = $_POST["soyad"];

    $conn = new mysqli("localhost", "root", "", "chatweb");

    if ($conn->connect_error) {
        die("Bağlantı hatası: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO users (kullanici_adi, sifre, ad, soyad, son_giris_tarihi) VALUES (?, ?, ?, ?, NOW())");
    $stmt->bind_param("ssss", $username, $password, $first_name, $last_name);

    if ($stmt->execute()) {
        $_SESSION["kullanici_id"] = $conn->insert_id;
        $_SESSION["ad"] = $first_name;
        $_SESSION["soyad"] = $last_name;
        $_SESSION["son_giris"] = date("Y-m-d H:i:s");

        header("Location: sohbet.php");
        exit();
    } else {
        echo "Kayıt başarısız: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
