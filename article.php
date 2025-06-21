<?php
session_start();
$conn = new mysqli("localhost", "root", "", "debate_news");
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$articleFound = false;
$articleData = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
  $articleId = intval($_GET['id']);

  $stmt = $conn->prepare("SELECT a.title, a.keyword, a.content, a.image_url, u.first_name, u.last_name, a.submitted, c.name
                            FROM article a
                            JOIN user u ON a.author_id = u.id
                            JOIN category c on a.category_id = c.id
                            WHERE a.id = ?");
  $stmt->bind_param("i", $articleId);
  $stmt->execute();
  $stmt->bind_result($title, $keyword, $content, $image_url, $authorFirstName, $authorLastName, $submitted, $category);

  if ($stmt->fetch()) {
    $articleFound = true;
    $articleData = compact('title', 'keyword', 'content', 'image_url', 'authorFirstName', 'authorLastName', 'submitted', 'category');
  }
  $stmt->close();
}
$conn->close();

function formatDateSpanish($dateStr) {
    $months = [
        1 => 'ENERO', 2 => 'FEBRERO', 3 => 'MARZO',
        4 => 'ABRIL', 5 => 'MAYO', 6 => 'JUNIO',
        7 => 'JULIO', 8 => 'AGOSTO', 9 => 'SEPTIEMBRE',
        10 => 'OCTUBRE', 11 => 'NOVIEMBRE', 12 => 'DICIEMBRE'
    ];
    $timestamp = strtotime($dateStr);
    $day = date('j', $timestamp);
    $month = $months[date('n', $timestamp)];
    $year = date('Y', $timestamp);
    return "$day DE $month DE $year";
}

?>

<!DOCTYPE html>
<html lang="en-US">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width">
  <title><?= $articleFound ? htmlspecialchars($articleData['title']) : "Debate" ?></title>
  <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
</head>

<body>
  <?php include 'components/header.php'; ?>

  <main>
    <?php if ($articleFound): ?>
      <article>
        <header>
          <p class="article-category"><?= htmlspecialchars($articleData['category']) ?></p>
          <h1><?= htmlspecialchars($articleData['title']) ?></h1>
          <p class="article-keyword"><?= htmlspecialchars($articleData['keyword']) ?></p>
          <p class="article-time"> <?= '• ' . formatDateSpanish($articleData['submitted']) ?></p>
        </header>
        <?php if ($articleData['image_url']): ?>
          <img class="article-img" src="img/<?= htmlspecialchars($articleData['image_url']) ?>" alt="Slika članka" style="max-width: 100%; height: auto;">
        <?php endif; ?>
        <div class="content"><?= nl2br(htmlspecialchars($articleData['content'])) ?></div>
        <p class= "article-author"><?= "<strong>Autor</strong>: " . htmlspecialchars($articleData['authorFirstName'] . ' ' . $articleData['authorLastName']) ?></p>
        <hr>
      </article>
    <?php else: ?>
      <section>
        <h2>Članak nije pronađen</h2>
        <p>Nažalost, traženi članak ne postoji ili je uklonjen.</p>
      </section>
    <?php endif; ?>
  </main>

  <?php include 'components/footer.php'; ?>
</body>

</html>