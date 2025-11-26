<?php
session_start();

if(!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit();
}

require_once(__DIR__ . "/koneksi.php");

$namaUser = $_SESSION['user']['nama'] ?? null;
$initial = $namaUser ? strtoupper(mb_substr($namaUser, 0, 1)) : 'U';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_review"])) {

    $service = $_POST["service"];
    $review_text = $_POST["review_text"];
    $user_id = $_SESSION["user"]["id"];

    // Upload foto
    $fotoName = $_FILES["foto"]["name"];
    $fotoTmp = $_FILES["foto"]["tmp_name"];
    $targetPath = "uploads_review/" . time() . "_" . $fotoName;

    move_uploaded_file($fotoTmp, $targetPath);

    $sql = "INSERT INTO reviews (user_id, service, foto, ulasan) VALUES (?, ?, ?, ?)";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("isss", $user_id, $service, $targetPath, $review_text);
    $stmt->execute();

    echo "<script>alert('Ulasan berhasil ditambahkan!'); window.location.href='index.php#reviews';</script>";
}

$reviews = [];
$result = $connection->query("SELECT * FROM reviews ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Barbro Barber</title>
    <link rel="stylesheet" href="style.css" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body>
    <header class="header">
        <div class="container">
            <div class="logo">BARBRO</div>

            <nav class="nav">
                <a href="#home">Home</a>
                <a href="#about">About</a>
                <a href="#services">Services</a>
                <a href="#contact">Contact</a>
                <a href="booking.php">Booking</a>
            </nav>

            <div class="profile-box">
                <span class="username"><?= $namaUser ?></span>
                <button class="profile-btn"><i class="bi bi-person-circle"></i></button>
            </div>
        </div>
    </header>

    <section id="home" class="hero">
        <div class="hero-left">
            <div class="hero-content">
                <h1>Barbro Barbershop</h1>
                <p>Where Style Meets Precision</p>
                <a href="booking.php" class="btn-gold">Book Now</a>
            </div>
        </div>
        <div class="hero-right">
            <img src="pelayanan.jpg" alt="Barbro Barber Services" />
        </div>
    </section>

    <section id="about" class="section about">
        <div class="container">
            <h2 class="section-title">About Us</h2>
            <p class="text">Barbro adalah barbershop bergaya modern dengan sentuhan klasik yang didirikan dengan visi menghadirkan pengalaman grooming premium di setiap kunjungan. Kami memadukan teknik potongan rambut profesional dengan pelayanan yang personal dan ramah, menciptakan suasana yang nyaman bagi setiap pelanggan. Dengan tim barber berpengalaman dan terlatih, kami mengutamakan presisi dalam setiap guntingan, kenyamanan selama proses perawatan, serta estetika maskulin yang elegan dengan perpaduan warna hitam-putih dan aksen gold yang mencerminkan kemewahan. Di Barbro, kami tidak hanya memberikan potongan rambut, tetapi juga pengalaman grooming yang membuat Anda tampil percaya diri dan stylish setiap hari.</p>
        </div>
    </section>

    <section id="reviews" class="section reviews">
        <div class="container">
            <h2 class="section-title">Customer Reviews</h2>

            <div class="reviews-wrapper">
                <?php if (count($reviews) > 0): ?>
                    <?php foreach ($reviews as $rev): ?>
                    <div class="review-card">
                        <img src="<?= $rev['foto'] ?>" alt="Review">
                        <h3><?= $rev['service'] ?></h3>
                        <p>“<?= $rev['ulasan'] ?>”</p>
                    </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p style="color:#ccc; width:100%; text-align:center;">Belum ada ulasan.</p>
                <?php endif; ?>
            </div>

            <!-- TOMBOL TAMBAH ULASAN -->
            <div style="text-align:center; margin-top:40px;">
                <button class="btn-gold" id="openReviewForm" style="cursor:pointer;">
                    Tambahkan Ulasan
                </button>
            </div>
        </div>
    </section>



    <section id="services" class="section services">
        <div class="container">
            <h2 class="section-title">Our Services</h2>
            <div class="grid">
                <div class="card">
                    <i class="bi bi-scissors"></i>
                    <h3>Haircut</h3>
                    <p>Classic & modern cuts with professional technique.</p>
                </div>
                <div class="card">
                    <i class="bi bi-person-fill"></i>
                    <h3>Beard Trim</h3>
                    <p>Sharp beard shaping & care treatment.</p>
                </div>
                <div class="card">
                    <i class="bi bi-brush"></i>
                    <h3>Styling</h3>
                    <p>Finishing & premium hair styling products.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="section contact">
        <div class="container">
            <h2 class="section-title">Contact Us</h2>
            
            <div class="contact-grid">
                <div class="contact-card">
                    <i class="bi bi-geo-alt-fill"></i>
                    <h3>Address</h3>
                    <p>Jl. Contoh No. 123<br>Tasikmalaya, West Java</p>
                </div>
                
                <div class="contact-card">
                    <i class="bi bi-envelope-fill"></i>
                    <h3>Email</h3>
                    <p>barbro@example.com<br>info@barbro.com</p>
                </div>
                
                <div class="contact-card">
                    <i class="bi bi-clock-fill"></i>
                    <h3>Open Hours</h3>
                    <p>Mon - Sat: 09:00 - 19:00<br>Sunday: Closed</p>
                </div>
            </div>
            
            <div class="map-container">
                <h3 class="map-title">Find Us Here</h3>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3957.164!2d108.2194!3d-7.3267!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zN8KwMTknMzYuMSJTIDEwOMKwMTMnMDkuOCJF!5e0!3m2!1sen!2sid!4v1234567890" 
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius: 10px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="container">
            <p>© 2025 Barbro Barbershop. All rights reserved.</p>
        </div>
    </footer>

    <div class="review-modal" id="reviewModal">
        <div class="review-modal-content">
            <h2>Tambah Ulasan</h2>

            <form method="POST" enctype="multipart/form-data">
                <input type="hidden" name="add_review" value="1">

                <label>Jenis Pelayanan</label>
                <select name="service" required>
                    <option value="Haircut">Haircut</option>
                    <option value="Beard Trim">Beard Trim</option>
                    <option value="Haircut + Beard">Haircut + Beard</option>
                </select>

                <label>Foto Hasil</label>
                <input type="file" name="foto" accept="image/*" required>

                <label>Ulasan</label>
                <textarea name="review_text" rows="4" required></textarea>

                <button type="submit" class="btn-gold" style="width:100%;">Kirim Ulasan</button>
            </form>

            <button class="btn-close" id="closeReviewForm">Tutup</button>
        </div>
    </div>

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
        document.getElementById("openReviewForm").onclick = function(){
            document.getElementById("reviewModal").style.display = "flex";
        }
        document.getElementById("closeReviewForm").onclick = function(){
            document.getElementById("reviewModal").style.display = "none";
        }
    </script>

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
    
    <script>
        // Fade in animation on scroll
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -100px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all sections
        document.querySelectorAll('.section').forEach(section => {
            section.classList.add('fade-in');
            observer.observe(section);
        });

        // Smooth scroll with fade in for navbar links
        document.querySelectorAll('.nav a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>