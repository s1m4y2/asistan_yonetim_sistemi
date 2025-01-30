<?php
session_start();
require_once('db_config.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $asistanId = null;

    // Bağlantıyı kontrol et
    if ($conn) {
        // Kullanıcı adı ve şifreyi veritabanında kontrol et
        $sql = "SELECT * FROM Kullanicilar WHERE KullaniciAdi = ? AND Sifre = ?";
        $params = array($username, $password);
        $conn = sqlsrv_connect($serverName, $connectionOptions);
        if ($conn === false) {
            die(print_r(sqlsrv_errors(), true));
        }

        $stmt = sqlsrv_query($conn, $sql, $params);


        if ($stmt === false) {
            // Sorgu hatası
            die(print_r(sqlsrv_errors(), true));
        }

        // Kullanıcı bulunduysa oturumu başlat ve ana sayfaya yönlendir
        if (sqlsrv_has_rows($stmt)) {
            $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
            $adSoyad = $row['AdSoyad'];
            $rolType = $row['RolType'];
            $kullaniciId = $row['ID'];
            // Oturum değişkenlerine kullanıcı adı ve AdSoyad'ı ata
            $_SESSION['kullaniciId'] = $kullaniciId;
            $_SESSION['username'] = $username;
            $_SESSION['adSoyad'] = $adSoyad;
            $_SESSION['rolType'] = $rolType;

            
            header('Location: home.php');
            exit;
        } else {
            // Kullanıcı bulunamadı, hata mesajı göster
            $error_message = "Invalid username or password.";
            echo $error_message;
        }

        

        // Sorgu sonuç kümesini serbest bırak
        sqlsrv_free_stmt($stmt);

        
        sqlsrv_close($conn);

    } else {
        die("Database connection error.");
    }
}
?>
