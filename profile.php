<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once(__DIR__ . "/koneksi.php");

$user_id = $_SESSION["user"]["id"];

// Ambil data user dari database
$sql_user = $connection->prepare("SELECT * FROM user WHERE id = ?");
$sql_user->bind_param("i", $user_id);
$sql_user->execute();
$data_user = $sql_user->get_result()->fetch_assoc();

$nama = $data_user["nama"];
$email = $data_user["email"];
$jenis_kelamin = $data_user["jenis_kelamin"] ?? "Laki-laki";
$foto = $data_user["foto"] ?? "default_profile.png";

// ===============================
// HANDLE UPDATE PROFILE
// ===============================
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $new_nama = $_POST["nama"];
    $new_email = $_POST["email"];
    $new_jenis_kelamin = $_POST["jenis_kelamin"];

    // Handle Foto
    $new_foto = $foto; // default: foto lama

    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === 0) {
        $file = $_FILES["foto"];
        $ext = pathinfo($file["name"], PATHINFO_EXTENSION);

        $new_foto = "profile_" . time() . "." . $ext;
        move_uploaded_file($file["tmp_name"], "uploads_profile/" . $new_foto);
    }

    // Update database
    $sql_update = $connection->prepare("
        UPDATE user SET nama=?, email=?, jenis_kelamin=?, foto=? WHERE id=?
    ");
    $sql_update->bind_param("ssssi", $new_nama, $new_email, $new_jenis_kelamin, $new_foto, $user_id);
    $sql_update->execute();

    // Update session
    $_SESSION["user"]["nama"] = $new_nama;
    $_SESSION["user"]["email"] = $new_email;

    echo "<script>alert('Profile berhasil diperbarui!'); window.location.href='profile.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Barbro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>

<header class="header">
    <div class="container">
        <div class="logo">BARBRO</div>

        <nav class="nav">
            <a href="index.php#home">Home</a>
            <a href="index.php#about">About</a>
            <a href="index.php#services">Services</a>
            <a href="booking.php">Booking</a>
            <a href="index.php#contact">Contact</a>
        </nav>

        <div class="profile-box">
            <span class="username"><?= $nama ?></span>
            <button class="profile-btn"><i class="bi bi-person-circle"></i></button>
        </div>
    </div>
</header>

<section class="profile-section">
    <div class="container">
        <form method="POST" enctype="multipart/form-data">
            <div class="profile-card">

                <div class="profile-top">
                    <div class="profile-pic-box">
                        <img src="uploads_profile/<?= $foto ?>" alt="Profile">
                    </div>

                    <div>
                        <label class="change-btn" for="foto">Change Picture</label>
                        <input type="file" id="foto" name="foto" accept="image/*" style="display:none;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= $nama ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= $email ?>" required>
                </div>

                <div class="form-group">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin">
                        <option value="Laki-laki" <?= $jenis_kelamin === "Laki-laki" ? "selected" : "" ?>>Laki-laki</option>
                        <option value="Perempuan" <?= $jenis_kelamin === "Perempuan" ? "selected" : "" ?>>Perempuan</option>
                    </select>
                </div>

                <div class="btn-row">
                    <button class="btn-apply" type="submit">Apply</button>
                    <button class="btn-cancel" type="button" onclick="window.location.href='index.php'">Cancel</button>
                </div>

            </div>
        </form>
    </div>
</section>

</body>
</html>
