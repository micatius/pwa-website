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
        include_once 'config.php';
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if ($conn->connect_error) {
            die("Database connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, first_name, last_name, level, password_hash FROM user WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($userId, $firstName, $lastName, $level, $passwordHash);
            $stmt->fetch();

            if (password_verify($password, $passwordHash)) {
                $_SESSION['user_id'] = $userId;
                $_SESSION['first_name'] = $firstName;
                $_SESSION['last_name'] = $lastName;
                $_SESSION['level'] = $level;
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
    <?php include 'components/header.php'; ?>

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

    <?php include 'components/footer.php'; ?>
</body>

</html>