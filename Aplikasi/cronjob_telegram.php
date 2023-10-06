<?php
date_default_timezone_set('Asia/Jakarta');
include 'config.php';
$botToken = '6563916978:AAFLo4ZPiG5gbmPkl0-0BJ39FgoqUYj_RG8';
$openWeatherApiKey = '2e19221fee5b1c4f6d6f283aa585f933';

function getWeatherData($cityname, $apiKey)
{
    $url = "https://api.openweathermap.org/data/2.5/weather?q=$cityname&appid=$apiKey";
    $response = file_get_contents($url);

    if ($response === false) {
        return null;
    }

    $data = json_decode($response, true);

    return $data;
}

// Mendapatkan waktu saat ini
$currentTime = date('H:i');

// Query untuk mendapatkan pengingat yang sesuai dengan waktu saat ini
$sql = "SELECT p.id, p.kota, p.telegram_id, pt.nama_ternak FROM pengingat_ternak pt 
        JOIN pengguna p ON pt.user_id = p.id
        WHERE pt.waktu_pengingat = '$currentTime'";
$result = $conn->query($sql);

if ($result === false) {
    die("Tidak bisa menjalankan query bosku, periksa ulang " . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $chatId = $row['telegram_id'];
        $cityname = $row['kota'];
        $namaTernak = $row['nama_ternak'];

        $weatherData = getWeatherData($cityname, $openWeatherApiKey);

        if ($weatherData !== null) {
            $currentTemperatureKelvin = $weatherData['main']['temp'];
            $currentTemperatureCelsius = $currentTemperatureKelvin - 273.15;

            // Menambahkan saran berdasarkan suhu cuaca
            if ($currentTemperatureCelsius < 10) {
                $saran = "Suhu saat ini sangat dingin " . number_format($currentTemperatureCelsius, 2) . " °C. Berikan lebih banyak makanan kepada ternak Anda.";
            } elseif ($currentTemperatureCelsius >= 10 && $currentTemperatureCelsius <= 30) {
                $saran = "Suhu saat ini normal " . number_format($currentTemperatureCelsius, 2) . " °C. Berikan makanan sesuai jadwal biasa.";
            } else {
                $saran = "Suhu saat ini sangat panas " . number_format($currentTemperatureCelsius, 2) . " °C. Pastikan ternak Anda memiliki akses ke air yang cukup.";
            }

            // Kirim pesan ke Telegram dengan saran cuaca
            $pesan = "🔔 Pemberitahuan Jadwal Memberi Makan $namaTernak. \n\n";
            $pesan .= "🕒 Pukul : <b>" . $currentTime . "</b>\n";
            $pesan .= "🐾 Jenis Ternak : <strong>" . $namaTernak . "</strong>\n";
            $pesan .= "📝 Rekomendasi : $saran \n\n";
            $pesan .= "- Automation by E-FeedReminder.id\n";

            // Kirim pesan ke Telegram
            $kirimStatus = kirimPesanTelegram($botToken, $chatId, $pesan);

            if ($kirimStatus === true) {
                echo "Pesan berhasil dikirim ke $chatId\n";
            } else {
                echo "Gagal mengirim pesan ke $chatId\n";
            }
        }
    }
} else {
    echo "Tidak ada jadwal pada jam $currentTime\n";
}

$conn->close();

function kirimPesanTelegram($botToken, $chatId, $pesan)
{
    $url = "https://api.telegram.org/bot$botToken/sendPhoto?chat_id=$chatId&parse_mode=html&photo=https://i.ibb.co/bdxdScQ/logo-tele.png&caption=" . urlencode($pesan);
    $result = file_get_contents($url);

    if ($result === false) {
        return false;
    }

    $data = json_decode($result, true);

    if (isset($data['ok']) && $data['ok'] === true) {
        return true;
    } else {
        return false;
    }
}
?>