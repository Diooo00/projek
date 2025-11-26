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

    $stmt->close();
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
        <div class="profile-card" style="max-width: 400px;">
            <div class="card-body p-4">

                <h3 class="text-center mb-4" style="color:#ceaf7f;">Register</h3>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger text-center" style="background:#330000; border-color:#ceaf7f44; color:#fff;">
                        <?= $error ?>
                    </div>
                <?php endif; ?>

                <form method="POST">

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="nama" required>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" required>
                            <option value="">-- Pilih --</option>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="password" required>
                    </div>

                    <div class="btn-row">
                        <button class="btn-apply w-100" type="submit">Register</button>
                    </div>
                </form>

                <p class="text-center mt-3">
                    Sudah punya akun? <a href="login.php" style="color:#ceaf7f;">Login</a>
                </p>

            </div>
        </div>
    </div>

</body>

</html>
