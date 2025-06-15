<?php
session_start(); // Oturum başlat

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["kullanici_adi"];
    $password = $_POST["sifre"];

    $conn = new mysqli("localhost", "root", "", "chatweb");

    if ($conn->connect_error) {
        die("Bağlantı hatası: " . $conn->connect_error);
    }

    // Kullanıcıyı kontrol et
    $stmt = $conn->prepare("SELECT kullanici_id, ad, soyad, sifre, son_giris_tarihi FROM users WHERE kullanici_adi = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($id, $first_name, $last_name, $hashed_password, $last_login);
        $stmt->fetch();

        if (password_verify($password, $hashed_password)) {
            // Başarılı giriş
            $_SESSION["kullanici_id"] = $id;
            $_SESSION["ad"] = $first_name;
            $_SESSION["soyad"] = $last_name;
            $_SESSION["son_giris"] = $last_login;

            // Son giriş tarihini güncelle
            $update_stmt = $conn->prepare("UPDATE users SET son_giris_tarihi = NOW() WHERE kullanici_id = ?");
            $update_stmt->bind_param("i", $id);
            $update_stmt->execute();
            $update_stmt->close();
            print("deneme");
            header("Location: sohbet.php");
            exit();
        } else {
            echo "Hatalı şifre.";
        }
    } else {
        echo "Kullanıcı bulunamadı.";
    }

    $stmt->close();
    $conn->close();
}
?>
