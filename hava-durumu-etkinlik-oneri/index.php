<?php
$conn = new mysqli("localhost", "root", "123456", "weather_assistant");
$conn->set_charset("utf8mb4");

// 1. Kullanıcı daha önce anket doldurmuş mu?
$result = $conn->query("SELECT id FROM user_preferences ORDER BY id DESC LIMIT 1");

if ($result->num_rows == 0) {
    // Kayıt yoksa ankete yönlendir
    header("Location: anket.php");
    exit();
}

// 2. Kayıt varsa Python'u çalıştır ve öneriyi al
$city = "Ankara";
$command = escapeshellcmd("python veri-cekme.py " . $city); 
$suggestion = shell_exec($command);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Hava Durumu Asistanı</title>
</head>
<body>
    <div class="card">
        <h1>☀️ Bugünün Önerisi</h1>
        <div class="suggestion">
            <?php echo htmlspecialchars($suggestion); ?>
        </div>
        <a href="anket.php" class="btn" style="width:auto; display:inline-block;">🔄 Tercihlerimi güncelle</a>
        <div class="footer-links">
            <span style="color:#9289b0;">Hava durumuna göre kişiselleştirilmiş</span>
        </div>
    </div>
</body>
</html>