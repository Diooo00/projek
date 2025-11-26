<?php
session_start();
require_once(__DIR__ . "/koneksi.php");

// Ambil nama user dari session
$namaUser = $_SESSION['user']['nama'] ?? null;

// =======================================
//           PROSES SIMPAN BOOKING
// =======================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nama = $_POST['name'] ?? '';
    $menu = $_POST['service'] ?? '';
    $tanggal = $_POST['date'] ?? '';
    $jam = $_POST['jam'] ?? '';   // ← FIX DISINI

    if (empty($nama) || empty($menu) || empty($tanggal) || empty($jam)) {
        echo "<script>alert('Semua field harus diisi!');</script>";
    } else {
        $created = date('Y-m-d H:i:s');

        $query = "INSERT INTO booking (nama, menu, tanggal, jam, created) VALUES (?, ?, ?, ?, ?)";
        $stmt = $connection->prepare($query);

        if ($stmt) {
            $stmt->bind_param("sssss", $nama, $menu, $tanggal, $jam, $created);

            if ($stmt->execute()) {
                echo "<script>
                    alert('Booking berhasil!');
                    window.location.href='booking.php';
                </script>";
                exit;
            } else {
                echo "<script>alert('Gagal menyimpan booking: " . $connection->error . "');</script>";
            }

            $stmt->close();
        } else {
            echo "<script>alert('Error prepare: " . $connection->error . "');</script>";
        }
    }
}

// =======================================
//          AMBIL DATA BOOKING USER
// =======================================
$bookingList = [];
if ($namaUser) {
    $query = "SELECT * FROM booking WHERE nama = ? ORDER BY tanggal DESC, jam DESC";

    // FIX: pakai $connection bukan $conn
    $stmt = $connection->prepare($query);

    if ($stmt) {
        $stmt->bind_param("s", $namaUser);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $bookingList[] = $row;
        }

        $stmt->close();
    } else {
        echo "Query error: " . $connection->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking</title>
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
            <span class="username"><?= $namaUser ?></span>
            <button class="profile-btn"><i class="bi bi-person-circle"></i></button>
        </div>
    </div>
</header>


<section class="section booking booking-page">
    <div class="container">

        <!-- LIST BOOKING -->
        <div class="booking-list-section">
            <h2 class="section-title">My Bookings</h2>

            <?php if (count($bookingList) > 0): ?>
                <div class="table-responsive">
                    <table class="booking-table">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Service</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Booked At</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($bookingList as $index => $b): ?>
                            <tr>
                                <td><?= $index + 1 ?></td>
                                <td><?= $b['menu'] ?></td>
                                <td><?= $b['tanggal'] ?></td>
                                <td><?= $b['jam'] ?></td>
                                <td><?= $b['created'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

            <?php else: ?>
                <div class="empty-state">
                    <i class="bi bi-calendar-x"></i>
                    <p>Tidak ada jadwal booking</p>
                </div>
            <?php endif; ?>

        </div>

        <!-- FORM BOOKING -->
        <div class="booking-form-section">
            <h2 class="section-title">Book a Session</h2>

            <form class="book-form" method="POST">
                <input type="text" name="name" value="<?= $namaUser ?>" required>
                <select name="service" required>
                    <option value="">Pilih Service</option>
                    <option value="Haircut">Haircut</option>
                    <option value="Beard Trim">Beard Trim</option>
                    <option value="Haircut + Beard">Haircut + Beard</option>
                </select>
                <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>
                <select name="jam" class="form-select" required>
                    <option value="">-- Pilih Jam --</option>
                    <option value="09:00">09:00</option>
                    <option value="09:30">09:30</option>
                    <option value="10:00">10:00</option>
                    <option value="10:30">10:30</option>
                    <option value="11:00">11:00</option>
                    <option value="11:30">11:30</option>
                    <option value="12:00">12:00</option>
                    <option value="12:30">12:30</option>
                    <option value="13:00">13:00</option>
                    <option value="13:30">13:30</option>
                    <option value="14:00">14:00</option>
                    <option value="14:30">14:30</option>
                    <option value="15:00">15:00</option>
                    <option value="15:30">15:30</option>
                    <option value="16:00">16:00</option>
                    <option value="16:30">16:30</option>
                    <option value="17:00">17:00</option>
                    <option value="17:30">17:30</option>
                    <option value="18:00">18:00</option>
                    <option value="18:30">18:30</option>
                    <option value="19:00">19:00</option>
                    <option value="19:30">19:30</option>
                    <option value="20:00">20:00</option>
                </select>
                <button type="submit" class="btn-gold">Confirm Booking</button>
            </form>
        </div>

    </div>
</section>

<!-- USER MENU MODAL -->
<div class="user-modal" id="userModal">
    <div class="user-modal-content">
        <h3>User Menu</h3>

        <a href="profile.php" class="user-btn"><i class="bi bi-person"></i> Profile</a>
        <a href="logout.php" class="user-btn"><i class="bi bi-box-arrow-right"></i> Logout</a>

        <button class="btn-close-user" id="closeUserModal">Tutup</button>
    </div>
</div>

<script>
    const userBtn = document.querySelector(".profile-btn");
    const userModal = document.getElementById("userModal");
    const closeUserModal = document.getElementById("closeUserModal");

    // buka modal
    userBtn.addEventListener("click", () => {
        userModal.style.display = "flex";
    });

    // tutup modal
    closeUserModal.addEventListener("click", () => {
        userModal.style.display = "none";
    });

    // klik luar modal → tutup
    window.addEventListener("click", (e) => {
        if (e.target === userModal) {
            userModal.style.display = "none";
        }
    });
</script>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>

</body>
</html>
