<?php
session_start();
require_once(__DIR__ . "/koneksi.php");
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['nama'];
    $password = $_POST['password'];

    $query = "SELECT * FROM user WHERE nama=?";
    $stmt = $connection->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();


    if ($row = $result->fetch_assoc()) {
        if ($password == $row['password']) {
            $_SESSION['akun'] = $row['nama'];
            header("Location: index.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Username tidak ditemukan!";
    }
}
?>

<form method="post">
    <h2>Login</h2>
    <input type="text" name="nama" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button type="submit">Masuk</button>
</form>