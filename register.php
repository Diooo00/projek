<?php
session_start();
require_once(__DIR__ . "/koneksi.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['nama'];
    $kelamin = $_POST['jenis_kelamin'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $query = "INSERT INTO user (nama,jenis_kelamin,email ,password) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("ssss", $username,$kelamin, $email, $password);


if ($stmt->execute()) {
    header("Location: login.php");
    exit();
} else {
    echo "Username sudah terdaftar!";
}
}
?>

<form method="post">
    <h2>Registrasi</h2>
    <input type="text" name="nama" placeholder="Username" required><br>
    <input type="text" name="jenis_kelamin" placeholder="Jenis kelamin" required><br>
    <input type="text" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Daftar</button>
</form>
