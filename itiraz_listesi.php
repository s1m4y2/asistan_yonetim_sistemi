<?php
session_start();

// Oturum kontrolü
if (!isset($_SESSION['kullaniciId'])) {
    // Oturum açılmamışsa, giriş sayfasına yönlendir
    header('Location: index.php');
    exit;
}

// Veritabanı bağlantısı
require_once('db_config.php');
$conn = sqlsrv_connect($serverName, $connectionOptions);
if ($conn === false) {
    die(print_r(sqlsrv_errors(), true));
}

$asistanSicilNo = $_SESSION['asistanId']; // Oturumdan asistan sicil no'sunu al

// Asistanın yaptığı itirazları sorgula
$sql = "
    SELECT I.ItirazAciklamasi, I.ItirazCevabi, I.ItirazDurumu, P.Ay, P.Yil
    FROM Itirazlar I
    JOIN Primler P ON I.PrimID = P.ID
    WHERE I.AsistanSicilNo = ?
";
$params = array($asistanSicilNo);
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
    <title>İtirazlarım</title>
    <link rel="stylesheet" href="style.css"> <!-- Eğer stil dosyanız varsa buraya ekleyin -->
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

        /* Link stilleri */
        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <h1>İtirazlarım</h1>

    <div class="itiraz-list">
        <table>
            <thead>
                <tr>
                    <th>Ay</th>
                    <th>Yıl</th>
                    <th>İtiraz Açıklaması</th>
                    <th>İtiraz Cevabı</th>
                    <th>İtiraz Durumu</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) { ?>
                    <tr>
                        <td><?php echo $row['Ay']; ?></td>
                        <td><?php echo $row['Yil']; ?></td>
                        <td><?php echo $row['ItirazAciklamasi']; ?></td>
                        <td><?php echo $row['ItirazCevabi'] ? $row['ItirazCevabi'] : 'Henüz Cevaplanmadı'; ?></td>
                        <td><?php echo $row['ItirazDurumu']; ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <?php
    sqlsrv_free_stmt($stmt);
    sqlsrv_close($conn);
    ?>

    <p><a href="home.php">Ana Sayfaya Dön</a></p>
</body>
</html>
