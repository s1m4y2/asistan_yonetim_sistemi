<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['kullaniciId'])) {
    // Oturum açılmamışsa, giriş sayfasına yönlendir
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısını sağlayın
require_once('db_config.php');
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Müşteri çağrılarını veritabanından alın
$sql = "SELECT Gorusmeler.*, Kullanicilar.AdSoyad AS AsistanAdSoyad
        FROM Gorusmeler
        INNER JOIN Asistanlar ON Gorusmeler.AsistanSicilNo = Asistanlar.ID
        INNER JOIN Kullanicilar ON Asistanlar.KullaniciId = Kullanicilar.ID";
$stmt = sqlsrv_query($conn, $sql);

if ($stmt === false) {
    // Sorgu hatası
    die(print_r(sqlsrv_errors(), true));
}

$asistanId = $_SESSION['asistanId'];
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Müşteri Çağrı Listesi</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .new-call {
            margin-top: 20px;
        }

        .new-call h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="date"],
        input[type="time"],
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Müşteri Çağrı Listesi</h1>
        <!-- Üst kısımda mevcut çağrılar listesi -->
        <div class="call-list">
            <table>
                <thead>
                    <tr>
                        <th>Asistan Adı Soyadı</th>
                        <th>Müşteri Adı Soyadı</th>
                        <th>Görüşme Konusu</th>
                        <th>Görüşme Tarihi</th>
                        <th>Başlama Saati</th>
                        <th>Bitiş Saati</th>
                        <th>Görüşme Durumu</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Mevcut çağrılar burada listelenecek -->
                    <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                        <tr>
                            <td><?php echo $row['AsistanAdSoyad']; ?></td>
                            <td><?php echo $row['MusteriAdSoyad']; ?></td>
                            <td><?php echo $row['GorusmeKonusu']; ?></td>
                            <td><?php echo $row['GorusmeTarihi']->format('Y-m-d'); ?></td>
                            <td><?php echo $row['BaslamaSaati']->format('H:i:s'); ?></td>
                            <td><?php echo $row['BitisSaati']->format('H:i:s'); ?></td>
                            <td><?php echo $row['GorusmeDurumu']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <div class="new-call">
            <h2>Yeni Çağrı Ekle</h2>
            <form action="yeni_cagri_ekle.php" method="post">
                <input type="hidden" id="asistan-sicil" value="<?php echo $asistanId; ?>" name="asistan_sicil">
                <div class="form-group">
                    <label for="musteri-ad">Müşteri Adı Soyadı:</label>
                    <input type="text" id="musteri-ad" name="musteri_ad" required>
                </div>
                <div class="form-group">
                    <label for="konu">Görüşme Konusu:</label>
                    <select id="konu" name="konu">
                        <option value="Arıza">Arıza</option>
                        <option value="Talep">Talep</option>
                        <option value="Bilgi">Bilgi</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tarih">Görüşme Tarihi:</label>
                    <input type="date" id="tarih" name="tarih" required>
                </div>
                <div class="form-group">
                    <label for="baslangic-saat">Başlama Saati:</label>
                    <input type="time" id="baslangic-saat" name="baslama_saat" required>
                </div>
                <div class="form-group">
                    <label for="bitis-saat">Bitiş Saati:</label>
                    <input type="time" id="bitis-saat" name="bitis_saat" required>
                </div>
                <div class="form-group">
                    <label for="durum">Görüşme Durumu:</label>
                    <select id="durum" name="durum">
                        <option value="Tamamlandı">Tamamlandı</option>
                        <option value="Takip Ediliyor">Takip Ediliyor</option>
                        <option value="Sorun Çözülmedi">Sorun Çözülmedi</option>
                    </select>
                </div>
                <button type="submit">Kaydet</button>
            </form>
        </div>
    </div>
</body>
</html>

