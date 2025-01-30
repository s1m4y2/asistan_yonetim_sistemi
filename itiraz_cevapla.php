<?php
session_start();

// PHPMailer'ı yükleyin
require 'vendor/autoload.php';

// Veritabanı bağlantısı
require_once('db_config.php');
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Formdan gelen cevap ve durum verilerini al
    $itirazId = $_POST['itirazId'];
    $cevap = $_POST['cevap'];
    $durum = $_POST['durum'];

    // Veritabanına cevabı kaydet
    $sql = "UPDATE Itirazlar SET ItirazCevabi = ?, ItirazDurumu = ? WHERE ID = ?";
    $params = array($cevap, $durum, $itirazId);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    // Takım liderinin ID'sini kullanarak grup yöneticisinin e-posta adresini al
    $sql = "SELECT Email FROM Kullanicilar WHERE ID = ?";
    $params = array($_SESSION['takimlideriId']);
    $stmt = sqlsrv_query($conn, $sql, $params);

    if ($stmt === false) {
        die(print_r(sqlsrv_errors(), true));
    }

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    $group_leader_email = $row['Email'];

    // PHPMailer nesnesini oluşturun
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    
    // SMTP ayarlarını yapılandırın
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'takimlideri111@gmail.com'; // Gönderen e-posta adresi
    $mail->Password = 'cfpexjncvzssmlio'; // Gmail şifreniz
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // E-posta bilgilerini ayarlayın
    $mail->setFrom('takimlideri111@gmail.com', 'Takım Lideri'); // Gönderen bilgisi
    $mail->addAddress($group_leader_email); // Alıcı e-posta adresi
    $mail->Subject = 'İtiraz Cevabı';
    $mail->Body = 'Bir itiraz cevaplandı. Lütfen kontrol ediniz.';

    // E-posta gönder
    if ($mail->send()) {
        echo "Bildirim e-postası başarıyla gönderildi.";
    } else {
        echo "Bildirim e-postası gönderilirken bir hata oluştu: " . $mail->ErrorInfo;
    }

    // Başarıyla cevaplandıktan sonra ana sayfaya yönlendir
    header("Location: home.php");
    exit;
}
?>
