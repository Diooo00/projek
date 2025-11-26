<?php
session_start();
if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once(__DIR__ . "/koneksi.php");

// pastikan folder upload ada
$uploadDir = __DIR__ . "/uploads_profile/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$user_id = (int)($_SESSION["user"]["id"] ?? 0);

// Ambil data user
$sql_user = $connection->prepare("SELECT * FROM user WHERE id = ?");
if (!$sql_user) {
    die("Prepare failed: " . $connection->error);
}
$sql_user->bind_param("i", $user_id);
$sql_user->execute();
$data_user = $sql_user->get_result()->fetch_assoc();
$sql_user->close();

if (!$data_user) {
    // user tidak ditemukan (rare) -> logout
    session_destroy();
    header("Location: login.php");
    exit();
}

// ambil nama/email/jenis dari DB, gunakan fallback jika kosong
$nama = $data_user["nama"] ?? "";
$email = $data_user["email"] ?? "";
$jenis_kelamin = $data_user["jenis_kelamin"] ?? "Laki-laki";
$foto = !empty($data_user["foto"]) ? $data_user["foto"] : "default_profile.png";

// sediakan nama user dari session supaya navbar aman
$namaUser = $_SESSION["user"]["nama"] ?? $nama;

// ================================
// HANDLE UPDATE PROFILE + GANTI FOTO
// ================================
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // ambil input (basic sanitasi)
    $new_nama = trim($_POST["nama"] ?? $nama);
    $new_email = trim($_POST["email"] ?? $email);
    $new_jenis_kelamin = $_POST["jenis_kelamin"] ?? $jenis_kelamin;

    // validasi email singkat
    if (!filter_var($new_email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Format email tidak valid'); window.location.href='profile.php';</script>";
        exit();
    }

    $new_foto = $foto; // default: tetap foto lama

    // Jika user upload foto baru
    if (isset($_FILES["foto"]) && $_FILES["foto"]["error"] === UPLOAD_ERR_OK) {
        $file = $_FILES["foto"];
        $allowed_ext = ['jpg','jpeg','png','webp'];
        $max_size = 1 * 1024 * 1024; 

        $origName = $file['name'];
        $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));

        if (!in_array($ext, $allowed_ext)) {
            echo "<script>alert('Format file tidak didukung. Gunakan jpg, jpeg, png, atau webp.'); window.location.href='profile.php';</script>";
            exit();
        }

        if ($file['size'] > $max_size) {
            echo "<script>alert('Ukuran file terlalu besar (max 2MB).'); window.location.href='profile.php';</script>";
            exit();
        }

        // generate nama file baru unik
        $new_foto = "profile_" . $user_id . "_" . time() . "." . $ext;
        $targetPath = $uploadDir . $new_foto;

        // pindahkan file ke folder uploads_profile
        if (!move_uploaded_file($file["tmp_name"], $targetPath)) {
            echo "<script>alert('Gagal mengunggah file. Coba lagi.'); window.location.href='profile.php';</script>";
            exit();
        }

        // hapus foto lama jika bukan default dan file ada
        if ($foto !== "default_profile.png") {
            $oldPath = $uploadDir . $foto;
            if (file_exists($oldPath) && is_file($oldPath)) {
                @unlink($oldPath);
            }
        }
    }

    // Update database
    $sql_update = $connection->prepare("
        UPDATE user 
        SET nama=?, email=?, jenis_kelamin=?, foto=? 
        WHERE id=?
    ");
    if (!$sql_update) {
        die("Prepare failed (update): " . $connection->error);
    }
    $sql_update->bind_param("ssssi", $new_nama, $new_email, $new_jenis_kelamin, $new_foto, $user_id);
    $exec_ok = $sql_update->execute();
    $sql_update->close();

    if (!$exec_ok) {
        echo "<script>alert('Gagal memperbarui profil.'); window.location.href='profile.php';</script>";
        exit();
    }

    // Update session values (agar navbar & session sync)
    $_SESSION["user"]["nama"] = $new_nama;
    $_SESSION["user"]["email"] = $new_email;

    echo "<script>alert('Profile berhasil diperbarui!'); window.location.href='profile.php';</script>";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>User Profile - Barbro</title>

    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;700&display=swap" rel="stylesheet">
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
            <span class="username"><?= htmlspecialchars($namaUser) ?></span>
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
                        <img src="uploads_profile/<?= htmlspecialchars($foto) ?>" alt="Profile">
                    </div>

                    <div>
                        <!-- tombol upload berubah otomatis -->
                        <label class="change-btn" for="foto" style="cursor:pointer;">
                            <?= ($foto === "default_profile.png") ? "Upload Foto" : "Ganti Foto" ?>
                        </label>

                        <input type="file" id="foto" name="foto" accept="image/*" style="display:none;">
                    </div>
                </div>

                <div class="form-group">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" value="<?= htmlspecialchars($nama) ?>" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
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

    <div class="user-modal" id="userModal">
        <div class="user-modal-content">
            <h3>User Menu</h3>

            <a href="profile.php" class="user-btn">
                <i class="bi bi-person-fill"></i> Profile
            </a>

            <a href="logout.php" class="user-btn">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>

            <button class="btn-close-user" id="closeUserModal">Close</button>
        </div>
    </div>

    <script>
        const userModal = document.getElementById("userModal");
        const openUserBtn = document.querySelector(".profile-btn");
        const closeUserBtn = document.getElementById("closeUserModal");

        // Buka modal saat tombol profile diklik
        openUserBtn.addEventListener("click", () => {
            userModal.style.display = "flex";
        });

        // Tutup modal saat tombol close diklik
        closeUserBtn.addEventListener("click", () => {
            userModal.style.display = "none";
        });

        // Tutup modal jika klik area luar
        window.addEventListener("click", (e) => {
            if (e.target === userModal) {
                userModal.style.display = "none";
            }
        });
    </script>

</section>

</body>
</html>
