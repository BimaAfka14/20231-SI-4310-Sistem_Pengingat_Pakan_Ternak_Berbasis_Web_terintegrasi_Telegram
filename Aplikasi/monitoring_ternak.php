<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("location: login.php");
    exit; // Keluar dari skrip agar tidak menjalankan bagian bawah
}
include 'config.php';
$user_id = $_SESSION['user_id'];

// Proses edit
if(isset($_POST['edit'])) {
    $id = $_POST['edit'];
    
    // Query untuk mengambil data berdasarkan ID
    $query = "SELECT id, waktu_pengingat, nama_ternak FROM pengingat_ternak WHERE user_id = '$user_id";
    $result = mysqli_query($conn, $query);
    
    if(!$result) {
        die("Query gagal: " . mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($result);
    
    // Tampilkan formulir edit
    echo "<h2>Edit Data Pengingat Ternak</h2>";
    echo "<form method='post'>";
    echo "<input type='hidden' name='id' value='" . $row['id'] . "'>";
    echo "User ID: " . $row['user_id'] . "<br>";
    echo "Waktu Pengingat: <input type='text' name='waktu_pengingat' value='" . $row['waktu_pengingat'] . "'><br>";
    echo "Nama Ternak: <input type='text' name='nama_ternak' value='" . $row['nama_ternak'] . "'><br>";
    echo "<input type='submit' name='update' value='Simpan'>";
    echo "</form>";
}

// Proses hapus
if(isset($_POST['delete'])) {
    $id = $_POST['delete'];
    
    // Query untuk menghapus data berdasarkan ID
    $delete_query = "DELETE FROM pengingat_ternak WHERE id = $id";
    
    if(mysqli_query($conn, $delete_query)) {
        header("Location: $_SERVER[PHP_SELF]"); // Redirect kembali ke halaman ini setelah menghapus data
        exit;
    } else {
        echo "Gagal menghapus data: " . mysqli_error($conn);
    }
}

// Proses update
if(isset($_POST['update'])) {
    $id = $_POST['id'];
    $nama_ternak_baru = $_POST['nama_ternak'];
    $waktu_pengingat_baru = $_POST['waktu_pengingat'];
    
    // Query untuk mengupdate data
    $update_query = "UPDATE pengingat_ternak SET nama_ternak = '$nama_ternak_baru', waktu_pengingat = '$waktu_pengingat_baru' WHERE id = $id";
    
    if(mysqli_query($conn, $update_query)) {
        header("Location: $_SERVER[PHP_SELF]"); // Redirect kembali ke halaman ini setelah mengupdate data
        exit;
    } else {
        echo "Gagal mengedit data: " . mysqli_error($conn);
    }
}

// Query untuk mengambil data dari tabel pengingat_ternak
$query = "SELECT id, user_id, waktu_pengingat, nama_ternak FROM pengingat_ternak";
$result = mysqli_query($conn, $query);

if(!$result) {
    die("Query gagal: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tabel Pengingat Ternak</title>
</head>
<body>
    <h1>Tabel Pengingat Ternak</h1>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>User ID</th>
            <th>Waktu Pengingat</th>
            <th>Nama Ternak</th>
            <th>Edit</th>
            <th>Hapus</th>
        </tr>
        <?php
        // Menampilkan data dari tabel pengingat_ternak
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>" . $row['id'] . "</td>";
            echo "<td>" . $row['user_id'] . "</td>";
            echo "<td>" . $row['waktu_pengingat'] . "</td>";
            echo "<td>" . $row['nama_ternak'] . "</td>";
            echo "<td><form method='post'><input type='hidden' name='edit' value='" . $row['id'] . "'><input type='submit' value='Edit'></form></td>";
            echo "<td><form method='post'><input type='hidden' name='delete' value='" . $row['id'] . "'><input type='submit' value='Hapus' onclick=\"return confirm('Apakah Anda yakin ingin menghapus data ini?');\"></form></td>";
            echo "</tr>";
        }

        // Menutup koneksi database
        mysqli_close($conn);
        ?>
    </table>
</body>
</html>
