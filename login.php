<?php
session_start();

if (isset($_SESSION["user"])) {
    header("Location: index.php");
    exit();
}

require_once(__DIR__ . "/koneksi.php");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM user WHERE email = ?";
    $stmt = $connection->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $row = $result->fetch_assoc();

        if (password_verify($password, $row["password"])) {
            $_SESSION["user"] = [
                "id" => $row["id"],
                "nama" => $row["nama"],
                "email" => $row["email"]
            ];

            header("Location: index.php");
            exit();
        }
    }

    $error = "Email atau password salah!";
}
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Barbro</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">

        <div class="profile-card" style="max-width: 480px;">

            <h3 class="text-center mb-4" style="color:#ceaf7f;">Login</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center" style="background:#330000; border-color:#ceaf7f55; color:white;">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <button class="btn-apply w-100" type="submit">Login</button>

            </form>

            <p class="text-center mt-3">
                Belum punya akun? <a href="register.php" style="color:#ceaf7f;">Register</a>
            </p>

        </div>

    </div>

</body>

</html>
