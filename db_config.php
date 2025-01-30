<?php
$serverName = "LAPTOP-UFQU32FD\\SQLEXPRESS"; // Çift ters eğik çizgi olarak kaçırılmış backslash
$connectionOptions = array(
    "Database" => "proje", // Veritabanı adı
    "CharacterSet" => "UTF-8" // Karakter kodlamasını belirtin
);

// MSSQL veritabanına bağlan
$conn = sqlsrv_connect($serverName, $connectionOptions);

// Bağlantı hatası kontrolü
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Bağlantıyı sonlandır
sqlsrv_close($conn);
?>
