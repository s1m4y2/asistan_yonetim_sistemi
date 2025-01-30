<?php
session_start();

// Veritabanı bağlantısı
require_once('db_config.php');
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Form verilerini al
$asistanSicilNoString = $_POST['asistan_sicil']; // Örnek olarak post edilen değeri alalım
$asistanSicilNoInt = intval($asistanSicilNoString); 
$musteri_ad = $_POST['musteri_ad'];
$konu = $_POST['konu'];
$tarih = $_POST['tarih'];
$baslama_saat = $_POST['baslama_saat'];
$bitis_saat = $_POST['bitis_saat'];
$durum = $_POST['durum'];

// Veritabanına yeni çağrıyı ekle
$sql = "INSERT INTO Gorusmeler (AsistanSicilNo, MusteriAdSoyad, GorusmeKonusu, GorusmeTarihi, BaslamaSaati, BitisSaati, GorusmeDurumu) 
        VALUES (?, ?, ?, ?, ?, ?, ?)";
$params = array($asistanSicilNoInt, $musteri_ad, $konu, $tarih, $baslama_saat, $bitis_saat, $durum);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    // Ekleme hatası
    die(print_r(sqlsrv_errors(), true));
}

// Veritabanı bağlantısını kapat
sqlsrv_close($conn);

// Yeni çağrı başarıyla eklendiğinde ana sayfaya yönlendir
header('Location: home.php');
exit;
?>
