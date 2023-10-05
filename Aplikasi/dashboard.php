<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location: index.php");
}
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $namaTernak = $_POST['nama_ternak'];
    $waktuPengingat = $_POST['waktu_pengingat'];


    if (empty($namaTernak) || empty($waktuPengingat)) {
        $error = "Field tidak boleh kosong, harus diisi.";
    } else {
        $user_id = $_SESSION['user_id'];
        $query = "INSERT INTO pengingat_ternak (user_id, nama_ternak, waktu_pengingat) 
                  VALUES ('$user_id', '$namaTernak', '$waktuPengingat')";

        if (mysqli_query($conn, $query)) {
            $success = "Data berhasil disimpan.";
        } else {
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    }
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM pengingat_ternak WHERE user_id='$user_id'";
$result = mysqli_query($conn, $query);
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
                        <a class="nav-link active" href="#">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="monitoring_ternak.php">Monitoring Ternak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="monitoring_suhu.php">Monitoring Suhu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="settings_profile.php">Edit Profile</a>
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
                        <input type="text" class="form-control" name="nama_ternak" placeholder="Input nama ternak..."
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-calendar" aria-hidden="true"></i></span>
                        <input type="time" class="form-control" name="waktu_pengingat" placeholder="Input waktu pakan..."
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
    
    <div id="login">
        <div class="container col-sm-6 mt-3 border rounded">
    <table class="table table-striped caption-top">
        <caption>LIST TERNAK</caption>
        <thead>
            <tr>
                <th>Nama Ternak</th>
                <th>Waktu Pakan</th>
            </tr>
        </thead>
        <tbody>
            <?php
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . $row["nama_ternak"] . "</td>";
                echo "<td>" . $row["waktu_pengingat"] . "</td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js.map"></script>
</body>
</html>
