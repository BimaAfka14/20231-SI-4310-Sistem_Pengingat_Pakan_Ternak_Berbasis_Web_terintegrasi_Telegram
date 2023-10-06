<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit; // Keluar dari skrip agar tidak menjalankan bagian bawah
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Ambil data pengguna dari database
$sql = "SELECT * FROM pengguna WHERE id='$user_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $existing_telegram_id = $row["telegram_id"];
    $existing_kota = $row["kota"];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari formulir HTML
    $telegram_id = $_POST["telegram_id"];
    $kota = $_POST["kota"];

    // Validasi input
    if (empty($telegram_id) || empty($kota)) {
        $error = "Harap isi semua kolom.";
    } else {
        // Lakukan update data ke database
        $sql = "UPDATE pengguna SET telegram_id='$telegram_id', kota='$kota' WHERE id='$user_id'";

        if ($conn->query($sql) === TRUE) {
            $success = "Data pengguna berhasil diubah.";
            // Update data sebelumnya setelah pembaruan
            $existing_telegram_id = $telegram_id;
            $existing_kota = $kota;
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Jangan tutup koneksi database di sini
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css.map">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <title>EFeedReminder.id</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-body-white border-bottom">
        <div class="container">
            <a class="navbar-brand" href="#">FeedR</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
                aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="monitoring_ternak.php">Monitoring Ternak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="monitoring_suhu.php">Monitoring Suhu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Edit Profile</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                <a href="logout.php"><button class="btn btn-danger" type="button">Logout</button></a>
                </form>
            </div>
        </div>
    </nav>
    <div id="login">
        <div class="container col-sm-6 mt-3 border rounded">
            <h2 class="text-center fs-1 pb-4"></h2>
            <div class="d-flex flex-column gap-2">
                <form action="" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-id-card" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" name="telegram_id" value="<?php echo isset($existing_telegram_id) ? $existing_telegram_id : ''; ?>"
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" name="kota" value="<?php echo isset($existing_kota) ? $existing_kota : ''; ?>"
                            aria-label="Password" aria-describedby="basic-addon1">
                    </div>
                    <div class="d-flex justify-content-end gap-2 mb-2">
                        <button type="submit" class="btn btn-primary ">SIMPAN</button>
                    </div>
                    <?php if (isset($error)) {
                        echo '<div class="alert alert-warning" role="alert">
                            ' . $error . '
                      </div>';
                    }elseif (isset($success)){
                        echo '<div class="alert alert-primary" role="alert">
                            ' . $success . '
                      </div>';
                    }
                    ?>
            </div>
        </div>
        </form>
    </div>
</body>
</html>
