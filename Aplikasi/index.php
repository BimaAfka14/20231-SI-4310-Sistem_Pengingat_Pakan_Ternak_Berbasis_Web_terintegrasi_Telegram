<?php
session_start();
if (isset($_SESSION['username'])) {
    header("location: dashboard.php");
    exit; // Keluar dari skrip agar tidak menjalankan bagian bawah
}
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Validasi input
    if (empty($username) || empty($password)) {
        $error = "-> Failed, Username dan password tidak boleh kosong.";
    } else {
        // Periksa pengguna dalam database
        $sql = "SELECT * FROM pengguna WHERE username='$username' AND password='$password'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) == 1) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];

            header("location: dashboard.php");
        } else {
            $error = "-> Failed, Username atau Password tidak valid.";
        }
    }
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
    <div id="login">
        <div class="container col-sm-6 mt-3 border rounded">
            <h2 class="text-center fs-1 pb-4">LOGIN</h2>
            <div class="d-flex flex-column gap-2">
                <form action="" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-id-card" aria-hidden="true"></i></span>
                        <input type="text" class="form-control" name="username" placeholder="Input username..."
                            aria-label="Username" aria-describedby="basic-addon1">
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1"><i class="fa fa-key" aria-hidden="true"></i></span>
                        <input type="password" class="form-control" name="password" placeholder="Input password..."
                            aria-label="Password" aria-describedby="basic-addon1">
                    </div>
                    <div class="d-flex justify-content-end gap-2 mb-2">
                        <button type="submit" class="btn btn-primary ">Login</button>
                        <a href="register.php"><button type="button" class="btn btn-success ">Register</button></a>
                    </div>
                    <?php if (isset($error)) {
                        echo '<div class="alert alert-warning" role="alert">
                            ' . $error . '
                      </div>';
                    }
                    ?>
            </div>
        </div>
        </form>
    </div>

    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js.map"></script>
</body>

</html>