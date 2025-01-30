<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['kullaniciId'])) {
    // Oturum açılmamışsa, giriş sayfasına yönlendir
    header('Location: index.php');
    exit;
}

require_once('db_config.php');

// Hata ayıklama için PHP hata raporlamayı etkinleştir
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Veritabanı bağlantısı
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            text-align: center;
        }

        h2 {
            margin-bottom: 20px;
        }

        h4 {
            margin-bottom: 20px;
        }

        .menu {
            margin-top: 20px;
        }

        .menu button {
            padding: 10px 20px;
            margin: 5px;
            border: none;
            border-radius: 5px;
            background-color: #4CAF50;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        .menu button:hover {
            background-color: #45a049;
        }

        a {
            display: block;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        p {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hoşgeldiniz, <?php echo $_SESSION['adSoyad']; ?>! </h2>
        <h4><?php echo $_SESSION['rolType']; ?></h4>

        <?php
        echo "<p>Rol Türü: " . $_SESSION['rolType'] . "</p>";  // Ek hata ayıklama çıktısı

        // Büyük/küçük harf duyarlılığına dikkat edin
        $rolType = strtolower($_SESSION['rolType']);

        if ($rolType == "asistan") {
            echo "<p>Asistan rolü tespit edildi.</p>";

            // Kullanıcının ad ve soyadına göre Asistan ID'sini sorgulayın
            $sql = "SELECT * FROM Asistanlar WHERE KullaniciId = ?";
            $params = array($_SESSION['kullaniciId']);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                // Sorgu hatası
                die(print_r(sqlsrv_errors(), true));
            }

            $asistanId = null;
            // Asistan ID'sini al
            if (sqlsrv_has_rows($stmt)) {
                $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                $asistanId = $row['ID'];
                echo "<p>Asistan ID: $asistanId</p>";
            } else {
                echo "<p>Asistan ID bulunamadı.</p>";
            }

            sqlsrv_free_stmt($stmt);

            $_SESSION['asistanId'] = $asistanId;

            ?>
            <div class="menu">
                <button onclick="window.location.href='musteri_cagri_listesi.php'">Müşteri Çağrı Listesi</button>
                <button onclick="window.location.href='aylik_prim_listesi.php'">Aylık Prim Listesi</button>
                <button onclick="window.location.href='itiraz_listesi.php'">İtiraz Listesi</button>
            </div>
            <?php
        } else if ($rolType == "takimlideri") {
            echo "<p>Takım lideri rolü tespit edildi.</p>";

            // Kullanıcı ID sine göre takım liderleri tablosunda eşleşen satırı al
            $sql = "SELECT * FROM TakimLiderleri WHERE KullaniciId = ?";
            $params = array($_SESSION['kullaniciId']);
            $stmt = sqlsrv_query($conn, $sql, $params);

            if ($stmt === false) {
                // Sorgu hatası
                die(print_r(sqlsrv_errors(), true));
            }

            $takimlideriId = null;
            // Takım Lideri ID'sini al
            if (sqlsrv_has_rows($stmt)) {
                $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
                $takimlideriId = $row['ID'];
                echo "<p>Takım Lideri ID: $takimlideriId</p>";
            } else {
                echo "<p>Takım Lideri ID bulunamadı.</p>";
            }

            sqlsrv_free_stmt($stmt);

            $_SESSION['takimlideriId'] = $takimlideriId;

            ?> 
            <div class="menu">
                <button onclick="window.location.href='itirazlari_yonet.php'">Asistan İtirazları</button>
            </div>
            <?php
        } else {
            echo "<p>Rol türü tanınmadı. Rol Türü: " . $_SESSION['rolType'] . "</p>";
        }

        sqlsrv_close($conn);
        ?>

        <a href="logout.php">Logout</a>
    </div>
</body>
</html>
