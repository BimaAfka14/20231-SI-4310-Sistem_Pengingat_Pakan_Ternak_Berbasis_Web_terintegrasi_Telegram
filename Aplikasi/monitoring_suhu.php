<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location: login.php");
}

include 'config.php'; // Pastikan Anda memiliki file config.php dengan koneksi ke database.


// Mengambil daftar pengingat ternak berdasarkan user_id
$user_id = $_SESSION['user_id'];
$query = "SELECT kota FROM pengguna WHERE id='$user_id'";
$result = mysqli_query($conn, $query);

// Ambil data dari hasil query
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $kota = $row['kota'];
} else {
    // Handle kesalahan jika query gagal
    echo "Error: " . mysqli_error($conn);
    exit();
}
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

    <title>Monitoring Suhu <?= $kota?> - EFeedReminder.id</title>
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
                        <a class="nav-link" href="dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="monitoring_ternak.php">Monitoring Ternak</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Monitoring Suhu</a>
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
        <div class="container col-sm-8 mt-3 mb-3 border rounded">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Suhu (°C)</th>
                    <th>Kondisi Cuaca</th>
                </tr>
            </thead>
            <tbody id="weatherData">
            </tbody>
        </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js.map"></script>
    <script>
        $(document).ready(function() {
            // Mengambil data dari API OpenWeatherMap
            $.ajax({
                url: 'https://api.openweathermap.org/data/2.5/forecast?q=<?= $kota?>&appid=2e19221fee5b1c4f6d6f283aa585f933',
                method: 'GET',
                dataType: 'json',
                success: function(data) {
                    // Menampilkan data cuaca dalam tabel
                    $.each(data.list, function(index, weather) {
                        var date = new Date(weather.dt_txt);
                        var temperatureCelsius = (weather.main.temp - 273.15).toFixed(2);
                        var weatherDescription = weather.weather[0].description;
                        if (weatherDescription == "few clouds") {
                            weatherDescription = "Sedikit Awan";
                        }else if (weatherDescription == "scattered clouds") {
                            weatherDescription = "Awan Berhamburan";
                        }else if (weatherDescription == "broken clouds") {
                            weatherDescription = "Awan Terpecah-pecah";
                        }else if (weatherDescription == "clear sky") {
                            weatherDescription = "Langit Cerah";
                        }else if (weatherDescription == "light rain") {
                            weatherDescription = "Hujan Ringan";
                        }else if (weatherDescription == "overcast clouds") {
                            weatherDescription = "Awan Mendung";
                        }else{
                            weatherDescription = "Undefined";
                        }

                        var tableRow = '<tr>' +
                            '<td>' + date.toLocaleString() + '</td>' +
                            '<td>' + temperatureCelsius + '°C</td>' +
                            '<td>' + weatherDescription + '</td>' +
                            '</tr>';

                        $('#weatherData').append(tableRow);
                    });
                },
                error: function() {
                    alert('Gagal mengambil data cuaca.');
                }
            });
        });
    </script>
</body>
</html>
