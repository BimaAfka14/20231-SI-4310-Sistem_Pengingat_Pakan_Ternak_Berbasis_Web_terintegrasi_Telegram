<?php
$errorMessage = $successMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'config.php';

    $username = $_POST["username"];
    $password = $_POST["password"];
    $telegram_id = $_POST["telegram_id"];
    $kota = $_POST["kota"];

    if (empty($username) || empty($password) || empty($telegram_id) || empty($kota)) {
        $errorMessage = "Tolong isi semua kolom yang diperlukan.";
    } else {
        $sql = "INSERT INTO pengguna (username, password, telegram_id, kota) VALUES ('$username', '$password', '$telegram_id', '$kota')";

        if ($conn->query($sql) === TRUE) {
            $successMessage = "Registrasi berhasil!";
        } else {
            $errorMessage = "Error saat mendaftar: " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EFeedReminder.id</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css.map">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .login-container {
            margin-top: 30px;
        }
    </style>
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
                        <a class="nav-link" href="#">Dashboard</a>
                    </li>
                </ul>
                <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <button class="btn btn-outline-success" type="submit">Search</button>
                </form>
            </div>
        </div>
    </nav>
    <div class="container login-container">
        <div class="row">
            <div class="col-md-6 order-md-1 order-2">
                <div id="login" class="border rounded p-3">
                    <h2 class="text-center fs-3 mb-3">SIGNUP</h2>
                    <form action="" method="POST">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Input username...">
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Input password...">
                        </div>
                        <div class="mb-3">
                            <label for="telegram_id" class="form-label">Telegram ID</label>
                            <input type="text" class="form-control" id="telegram_id" name="telegram_id" placeholder="Input telegram_id...">
                        </div>
                        <div class="mb-3">
                            <label for="text" class="form-label">Kota</label>
                            <input type="text" class="form-control" id="kota" name="kota" placeholder="Input kota...">
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                            <a href="index.php" class="btn btn-success">Login</a>
                        </div>
                        <?php 
                        if (!empty($errorMessage)) {
                            echo '<div class="alert alert-danger mt-3" role="alert">' . $errorMessage . '</div>';
                        }
                        if (!empty($successMessage)) {
                            echo '<div class="alert alert-success mt-3" role="alert">' . $successMessage . '</div>';
                        }
                        ?>
                    </form>
                </div>
            </div>
            <div class="col-md-6 order-md-2 order-1">
                <div class="border rounded p-3">
                    <h2 class="text-center fs-3">Welcome to sign up page</h2>
                    <p class="text-center">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla nec odio et justo sollicitudin iaculis. Sed at metus at turpis dignissim faucibus.</p>
                </div>
            </div>
        </div>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js.map"></script>
</body>
</html>
