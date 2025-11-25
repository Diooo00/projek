<?php
session_start();
require_once(__DIR__ . "/koneksi.php");

$namaUser = $_SESSION['user']['nama'] ?? null;
$initial = $namaUser ? strtoupper(mb_substr($namaUser, 0, 1)) : 'U';
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
                <a href="booking.php">Booking</a>
                <a href="#contact">Contact</a>
            </nav>

            <div class="profile-box">
                <span class="username">Hallo, <?= $namaUser ?? 'User123' ?></span>
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