<?php
session_start();

// Veritabanı bağlantısı
require_once('db_config.php');
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Form verilerini al
$itirazSebebi = $_POST['itiraz'];
$primId = $_POST['primId'];

$asistanId = $_SESSION['asistanId']; // Oturumdan asistan ID'sini al
$ay = date('m'); // Geçerli ayı al
$yil = date('Y'); // Geçerli yılı al

// İtirazı veritabanına ekle
$sql = "INSERT INTO Itirazlar (AsistanSicilNo, PrimId, ItirazAciklamasi) VALUES (?, ?, ?)";
$params = array($asistanId, $primId, $itirazSebebi);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Veritabanı bağlantısını kapat
sqlsrv_close($conn);

// İtiraz başarıyla eklendiğinde ana sayfaya yönlendir
header('Location: home.php');
exit;
?>
