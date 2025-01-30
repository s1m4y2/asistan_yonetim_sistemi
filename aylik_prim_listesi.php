<?php


session_start();

// Oturum kontrolü
if (!isset($_SESSION['kullaniciId'])) {
    // Oturum açılmamışsa, giriş sayfasına yönlendir
    header('Location: index.php');
    exit;
}



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aylık Prim Listesi</title>
    <style>
        /* Genel stil */
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        /* Container stil */
        .container {
            max-width: 800px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        /* Tablo stil */
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

        /* Pop-up stil */
        .popup-container {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 9999;
        }

        .popup-container h2 {
            margin-top: 0;
        }

        .popup-container button {
            margin-top: 10px;
            padding: 5px 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 3px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <h1>Aylık Prim Listesi</h1>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Asistan Sicil No</th>
                <th>Ay</th>
                <th>Yıl</th>
                <th>Prim Miktarı</th>
                <th>İtiraz Et</th>
            </tr>
        </thead>
        <tbody>
            <?php
                // Veritabanı bağlantısını sağlayın
                require_once('db_config.php');
                $conn = sqlsrv_connect($serverName, $connectionOptions);
                if ($conn === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                $asistanId = $_SESSION['asistanId'];

                // Asistanın primlerini sorgula
                $sql = "
                    SELECT P.ID, P.AsistanSicilNo, P.Ay, P.Yil, P.PrimMiktari, K.AdSoyad
                    FROM Primler P
                    JOIN Asistanlar A ON P.AsistanSicilNo = A.ID
                    JOIN Kullanicilar K ON A.KullaniciId = K.ID
                    WHERE A.ID = ?
                ";
                $params = array($asistanId);
                $stmt = sqlsrv_query($conn, $sql, $params);

                if ($stmt === false) {
                    die(print_r(sqlsrv_errors(), true));
                }

                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    echo "<tr>";
                    echo "<td>" . $row['ID'] . "</td>";
                    echo "<td>" . $row['AsistanSicilNo'] . "</td>";
                    echo "<td>" . $row['Ay'] . "</td>";
                    echo "<td>" . $row['Yil'] . "</td>";
                    echo "<td>" . $row['PrimMiktari'] . "</td>";
                    // Sadece son aya ait prim için itiraz et butonu ekle
                    if ($row['Ay'] == date('m') && $row['Yil'] == date('Y')) {
                        echo "<td><button onclick=\"openPopup(" . $row['ID'] . ")\">İtiraz Et</button></td>";
                    } else {
                        echo "<td></td>";
                    }
                    echo "</tr>";
                }
                // statement ı serbest bırakıyoruz ve bağlantıyı kapatıyoruz
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
                ?>
        </tbody>
    </table>
                <!-- Pop-up -->
    <div id="popup" class="popup-container">
        <form action="aylik_prim_itiraz.php" method="post">
            <h2>İtiraz Et</h2>
            <input type="hidden" id="primId" name="primId">
            <label for="itiraz">İtiraz Sebebi:</label>
            <textarea id="itiraz" name="itiraz" required></textarea><br><br>
            <input type="submit" value="Gönder">
            <button type="button" onclick="closeItirazPopup()">Kapat</button>
        </form>
    </div>

    <script>
    function openPopup(primId) {
        var popup = document.getElementById('popup');
        document.getElementById('primId').value = primId;
        popup.style.display = 'block';
    }

    function closeItirazPopup() {
            document.getElementById('popup').style.display = 'none';
    }

    
</script>
    <?php
    
    ?>

</body>
</html>
