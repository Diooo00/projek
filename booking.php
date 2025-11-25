<?php
session_start();
require_once(__DIR__ . "/koneksi.php");

$namaUser = $_SESSION['user']['nama'] ?? null;
$initial = $namaUser ? strtoupper(mb_substr($namaUser, 0, 1)) : 'U';

// Proses form booking jika ada POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $service = $_POST['service'] ?? '';
    $date = $_POST['date'] ?? '';
    $time = $_POST['time'] ?? '';
    
    // TODO: Simpan ke database
    // Contoh query:
    // $query = "INSERT INTO bookings (name, phone, service, date, time) VALUES (?, ?, ?, ?, ?)";
    // $stmt = $conn->prepare($query);
    // $stmt->bind_param("sssss", $name, $phone, $service, $date, $time);
    // $stmt->execute();
    
    // Redirect atau tampilkan pesan sukses
    echo "<script>alert('Booking berhasil!'); window.location.href='index.php';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Booking - Barbro Barber</title>
    <link rel="stylesheet" href="style.css" />
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
                <span class="username">Hallo, <?= $namaUser ?? 'User123' ?></span>
                <button class="profile-btn"><i class="bi bi-person-circle"></i></button>
            </div>
        </div>
    </header>

    <section class="section booking">
        <div class="container">
            <h2 class="section-title">Book a Session</h2>
            <p class="booking-subtitle">Pilih layanan dan waktu yang Anda inginkan</p>
            
            <form class="book-form" method="POST">
                <input type="text" name="name" placeholder="Your Name" value="<?= $namaUser ?? '' ?>" required>
                
                <input type="text" name="phone" placeholder="Phone Number" required>
                
                <select name="service" required>
                    <option value="">Select Service</option>
                    <option value="Haircut">Haircut - Rp 50.000</option>
                    <option value="Beard Trim">Beard Trim - Rp 30.000</option>
                    <option value="Haircut + Beard">Haircut + Beard - Rp 75.000</option>
                    <option value="Styling">Styling - Rp 40.000</option>
                </select>
                
                <input type="date" name="date" min="<?= date('Y-m-d') ?>" required>
                
                <select name="time" required>
                    <option value="">Select Time</option>
                    <option value="09:00">09:00 AM</option>
                    <option value="10:00">10:00 AM</option>
                    <option value="11:00">11:00 AM</option>
                    <option value="12:00">12:00 PM</option>
                    <option value="13:00">01:00 PM</option>
                    <option value="14:00">02:00 PM</option>
                    <option value="15:00">03:00 PM</option>
                    <option value="16:00">04:00 PM</option>
                    <option value="17:00">05:00 PM</option>
                    <option value="18:00">06:00 PM</option>
                    <option value="19:00">07:00 PM</option>
                </select>
                
                <button type="submit" class="btn-gold">Confirm Booking</button>
                <a href="index.php" class="btn-back">Back to Home</a>
            </form>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>© 2025 Barbro Barbershop. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>