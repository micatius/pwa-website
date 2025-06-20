<?php
session_start();
$registration = false;
$errors = [];
$values = [
  'first-name' => '',
  'last-name' => '',
  'username' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $firstName = trim($_POST['first-name']);
  $lastName = trim($_POST['last-name']);
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $passwordVerify = $_POST['password-verify'];


  $values['first-name'] = htmlspecialchars($firstName);
  $values['last-name'] = htmlspecialchars($lastName);
  $values['username'] = htmlspecialchars($username);

  if (empty($firstName)) $errors[] = 'Ime je obavezno.';
  if (empty($lastName)) $errors[] = 'Prezime je obavezno.';
  if (empty($username)) $errors[] = 'Korisničko ime je obavezno.';
  if (empty($password)) $errors[] = 'Lozinka je obavezna.';
  if ($password !== $passwordVerify) $errors[] = 'Lozinke se ne podudaraju.';

  if (!$errors) {
    $conn = new mysqli("localhost", "root", "", "debate_news");
    if ($conn->connect_error) {
      die("Database connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT id FROM user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
      $errors[] = 'Korisničko ime već postoji.';
    }

    $stmt->close();

    if (!$errors) {
      $passwordHash = password_hash($password, PASSWORD_DEFAULT);

      $stmt = $conn->prepare("INSERT INTO user (first_name, last_name, username, password_hash) VALUES (?, ?, ?, ?)");
      $stmt->bind_param("ssss", $firstName, $lastName, $username, $passwordHash);

      if ($stmt->execute()) {
        $registration = true;
      } else {
        $errors[] = 'Greška prilikom spremanja korisnika.';
      }

      $stmt->close();
    }

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
      <h2>REGISTRATION</h2>
      <label for="first-name">Ime:</label>
      <input type="text" name="first-name" id="first-name" value="<?= $values['first-name'] ?>">
      <label for="last-name">Prezime:</label>
      <input type="text" name="last-name" id="last-name" value="<?= $values['last-name'] ?>">
      <label for="username">Korisničko ime:</label>
      <input type="text" name="username" id="username" value="<?= $values['username'] ?>">
      <label for="password">Lozinka:</label>
      <input type="password" name="password" id="password">
      <label for="password-verify">Potvrdite lozinku:</label>
      <input type="password" name="password-verify" id="password-verify">
      <button>REGISTRACIJA</button>

      <?php if (!empty($errors)): ?>
        <ul style="color:red;">
          <?php foreach ($errors as $error): ?>
            <li><?= $error ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
      
      <?php if ($registration): ?>
        <p style="color: green;">Registracija uspješna! Preusmjeravanje na login za 3 sekunde...</p>
        <script>
          setTimeout(function() {
            window.location.href = 'login.php';
          }, 3000);
        </script>
      <?php endif; ?>

    </form>
  </main>

  <?php include 'components/footer.php'; ?>
</body>

</html>