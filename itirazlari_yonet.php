<?php
session_start();


// Veritabanı bağlantısı
require_once('db_config.php');
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

// Takım liderine bağlı asistanları ve onların itirazlarını sorgula
$sql = "
    SELECT I.ID AS ItirazID, A.ID AS AsistanSicilNo, K.AdSoyad AS AsistanAdSoyad, I.ItirazAciklamasi, P.Ay, P.Yil, I.ItirazDurumu
    FROM Itirazlar I
    JOIN Primler P ON I.PrimID = P.ID
    JOIN Asistanlar A ON I.AsistanSicilNo = A.ID
    JOIN Kullanicilar K ON A.KullaniciId = K.ID
    WHERE A.TakimLideriId = ?
";
$params = array($_SESSION['takimlideriId']);
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    die(print_r(sqlsrv_errors(), true));
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>İtiraz Yönetimi</title>
    <link rel="stylesheet" href="style.css"> <!-- Eğer stil dosyanız varsa buraya ekleyin -->
    <script>
        function openPopup(itirazId) {
            var popup = document.getElementById('popup');
            document.getElementById('itirazId').value = itirazId;
            popup.style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
        }
    </script>
    <style>
        /* Genel stiller */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
        }

        /* İtiraz listesi stilleri */
        .itiraz-list {
            width: 80%;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Pop-up stilleri */
        .popup-container {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .popup-container form {
            display: flex;
            flex-direction: column;
        }

        .popup-container label {
            margin-top: 10px;
        }

        .popup-container textarea, .popup-container select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }

        .popup-container input[type="submit"], .popup-container button {
            margin-top: 20px;
            padding: 10px;
            cursor: pointer;
        }

        /* Link stilleri */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
<body>
    <h1>İtiraz Yönetimi</h1>

    <div class="itiraz-list">
        <table>
            <thead>
                <tr>
                    <th>Asistan Sicil No</th>
                    <th>Asistan Ad Soyad</th>
                    <th>Ay</th>
                    <th>Yıl</th>
                    <th>İtiraz Açıklaması</th>
                    <th>İtiraz Durumu</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['AsistanSicilNo']; ?></td>
                        <td><?php echo $row['AsistanAdSoyad']; ?></td>
                        <td><?php echo $row['Ay']; ?></td>
                        <td><?php echo $row['Yil']; ?></td>
                        <td><?php echo $row['ItirazAciklamasi']; ?></td>
                        <td><?php echo $row['ItirazDurumu']; ?></td>
                        <td>
                            <?php if ($row['ItirazDurumu'] == 'Bekliyor') { ?>
                                <button onclick="openPopup(<?php echo $row['ItirazID']; ?>)">İtiraz Cevapla</button>
                            <?php } ?>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <!-- Pop-up -->
    <div id="popup" class="popup-container" style="display:none;">
        <form action="itiraz_cevapla.php" method="post">
            <h2>İtiraz Cevapla</h2>
            <input type="hidden" id="itirazId" name="itirazId">
            <label for="cevap">Cevap:</label>
            <textarea id="cevap" name="cevap" required></textarea><br><br>
            <label for="durum">Durum:</label>
            <select id="durum" name="durum" required>
                <option value="Onaylandı">Onayla</option>
                <option value="Reddedildi">Reddet</option>
            </select><br><br>
            <input type="submit" value="Gönder">
            <button type="button" onclick="closePopup()">Kapat</button>
        </form>
    </div>

    <?php
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    ?>

    <p><a href="home.php">Ana Sayfaya Dön</a></p>
</body>
</html>
