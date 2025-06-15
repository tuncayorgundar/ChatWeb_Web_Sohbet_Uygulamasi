<?php
session_start();
if (!isset($_SESSION["kullanici_id"])) {
    header("Location: ../login.html"); // Giriş yapılmamışsa yönlendirme
    exit();
}
?>

<html>
<head>
    <title>Tuncay</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <div id="menuIcon">☰</div>

    <!-- Yan Menü -->
    <div id="sideMenu">
        <ul>
            <li>Kullanici Adi: <?php echo htmlspecialchars($_SESSION["ad"] . " " . $_SESSION["soyad"]); ?></li>
            <li>Son Giriş: <?php echo htmlspecialchars($_SESSION["son_giris"]); ?></li>
            <li>Durum: Çevrimiçi</li>
        </ul>
    </div>
    <div id="chatContainer"></div>
    <form id="messageForm">
        <input type="text" id="messageInput" placeholder="Mesaj yazınız..." required>
        <button type="button" id="sendMessageButton">Gönder</button>
    </form>
    <script src="../js/app.js"></script>
</body>
</html>
