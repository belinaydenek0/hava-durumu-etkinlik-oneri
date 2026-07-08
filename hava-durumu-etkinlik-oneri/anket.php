<?php
// Veritabanı bağlantısı
$conn = new mysqli("localhost", "root", "123456", "weather_assistant");
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $prefers_new = isset($_POST['prefers_new']) ? 1 : 0;
    $favorite_activities = $_POST['favorite_activities'];

    // Veritabanına kaydet
    $stmt = $conn->prepare("INSERT INTO user_preferences (prefers_new_activities, favorite_activities) VALUES (?, ?)");
    $stmt->bind_param("is", $prefers_new, $favorite_activities);
    $stmt->execute();

    echo "<div class='card' style='text-align:center;'>
            <h2>✅ Tercihlerin kaydedildi!</h2>
            <a href='index.php' class='btn'>Ana sayfaya dön</a>
          </div>";
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Kişisel Tercih Anketi</title>
</head>
<body>
    <div class="card">
        <h2>🌟 Tercihlerini belirle</h2>
        <form method="POST" action="anket.php">
            <div class="form-group">
                <label>Yeni aktiviteler denemeyi sever misin?</label>
                <div class="switch-wrapper">
                    <label class="switch">
                        <input type="checkbox" name="prefers_new" value="1">
                        <span class="slider"></span>
                    </label>
                    <span class="switch-label">Evet, severim</span>
                </div>
            </div>

            <div class="form-group">
                <label for="activities">Nelerden hoşlanırsın? <span style="font-weight:400;">(Örn: Kodlama, Kitap, Spor)</span></label>
                <textarea id="activities" name="favorite_activities" required placeholder="Örn: Kitap okumak, yürüyüş yapmak..."></textarea>
            </div>

            <button type="submit" class="btn">Kaydet</button>
        </form>
        <div class="footer-links">
            <a href="index.php">Ana sayfaya dön</a>
        </div>
    </div>
</body>
</html>