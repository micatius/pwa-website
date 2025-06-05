<?php
session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username)) {
        $errors[] = 'Korisničko ime je obavezno.';
    }
    if (empty($password)) {
        $errors[] = 'Lozinka je obavezna.';
    }

    if (!$errors) {
        $conn = new mysqli("localhost", "root", "", "debate_news");
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, password_hash FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId, $passwordHash);
            $stmt->fetch();

            if (password_verify($password, $passwordHash)) {
                $_SESSION['user_id'] = $userId;
                header('Location: admin.php');
                exit;
            } else {
                $errors[] = 'Pogrešna lozinka.';
            }
        } else {
            $errors[] = 'Korisničko ime ne postoji.';
        }

        $stmt->close();
        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Debate</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
</head>

<body>
    <header class="page-header">
        <div class="logo"><?php include 'resource/debate-logo.svg.html'; ?></div>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">HOME</a></li>
                <li><a href="mundo.php" class="<?= basename($_SERVER['PHP_SELF']) == 'mundo.php' ? 'active' : '' ?>">MUNDO</a></li>
                <li><a href="deporte.php" class="<?= basename($_SERVER['PHP_SELF']) == 'deporte.php' ? 'active' : '' ?>">DEPORTE</a></li>
                <li><a href="admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">ADMINISTRACIJA</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <form action="" method="post">
          <h2>Login</h2>
          <label for="username">Korisničko ime:</label>
          <input type="text" name="username" id="username">
          <label for="password">Lozinka:</label>
          <input type="password" name="password" id="password">
          <button>PRIJAVA</button>
          <a href="registration.php">Registracija za nove korisnike</a>
        </form>
    </main>

    <footer class="page-footer">
        <div class="copyright">
            <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
        </div>
    </footer>




</body>

</html>