<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once(__DIR__ . "/koneksi.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nama         = $_POST["nama"];
    $email        = $_POST["email"];
    $jenisKelamin = $_POST["jenis_kelamin"];
    $password     = $_POST["password"];

    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO user (nama, jenis_kelamin, email, password) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("ssss", $nama, $jenisKelamin, $email, $password_hash);

    if ($stmt->execute()) {
        echo "<script>
                alert('Akun berhasil dibuat! Silakan login.');
                window.location.href='login.php';
              </script>";
        exit();
    } else {
        $error = "Registrasi gagal! Email atau nama mungkin sudah digunakan.";
    }
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Barbro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">

        <div class="profile-card" style="max-width: 520px;">

            <h3 class="text-center mb-4" style="color:#ceaf7f;">Register</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center" style="background:#330000; border-color:#ceaf7f55; color:white;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input name="nama" type="text" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="Laki-laki">Laki-laki</option>
                        <option value="Perempuan">Perempuan</option>
                </select>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <button class="btn-apply w-100" type="submit">Register</button>

            </form>

            <p class="text-center mt-3">
                Sudah punya akun? <a href="login.php" style="color:#ceaf7f;">Login</a>
            </p>

        </div>

    </div>

</body>

</html>
