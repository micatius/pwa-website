<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "debate_news");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$articles = $conn->query("SELECT id, title FROM article");
$categories = $conn->query("SELECT id, name FROM category");

$selectedArticle = null;
$articleData = [
    'title' => '',
    'keyword' => '',
    'content' => '',
    'image' => '',
    'category_id' => '',
    'archive' => 0
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['article-id']) && $_POST['article-id'] !== 'new') {
        $stmt = $conn->prepare("SELECT title, keyword, content, image, category_id, archive FROM article WHERE id = ?");
        $stmt->bind_param("i", $_POST['article-id']);
        $stmt->execute();
        $stmt->bind_result($title, $keyword, $content, $image, $category_id, $archive);
        if ($stmt->fetch()) {
            $articleData = compact('title', 'keyword', 'content', 'image', 'category_id', 'archive');
            $selectedArticle = $_POST['article-id'];
        }
        $stmt->close();
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
        <form action="admin.php" method="post" enctype="multipart/form-data">
            <h2>Uređivanje članaka</h2>
            <label for="article-id">Odaberite članak:</label>
            <select name="article-id" id="article-id" onchange="this.form.submit()">
                <option value="new">Novi članak</option>
                <?php while ($row = $articles->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= $selectedArticle == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['title']) ?>
                    </option>
                <?php endwhile; ?>
            </select>
        </form>

        <form action="save_article.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= htmlspecialchars($selectedArticle ?? '') ?>">

            <label for="title">Naslov:</label>
            <input type="text" name="title" id="title" value="<?= htmlspecialchars($articleData['title']) ?>">

            <label for="keyword">Ključna riječ:</label>
            <input type="text" name="keyword" id="keyword" value="<?= htmlspecialchars($articleData['keyword']) ?>">

            <label for="content">Sadržaj:</label>
            <textarea name="content" id="content"><?= htmlspecialchars($articleData['content']) ?></textarea>

            <label for="image">Slika članka:</label>
            <input type="file" name="image" id="image">
            <?php if (!empty($articleData['image'])): ?>
                <p>Trenutna slika: <?= htmlspecialchars($articleData['image']) ?></p>
            <?php endif; ?>

            <label for="category">Kategorija:</label>
            <select name="category" id="category">
                <?php while ($row = $categories->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>" <?= $articleData['category_id'] == $row['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($row['name']) ?>
                    </option>
                <?php endwhile; ?>
            </select>

            <label>
                <input type="checkbox" name="archive" <?= $articleData['archive'] ? 'checked' : '' ?>>
                Arhiviraj članak
            </label>

            <br><br>
            <button type="submit">Spremi članak</button>

            <?php if ($selectedArticle): ?>
                <button type="submit" name="delete" value="1" onclick="return confirm('Jeste li sigurni da želite obrisati članak?')">Obriši članak</button>
            <?php endif; ?>
        </form>
    </main>

    <footer class="page-footer">
        <div class="copyright">
            <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
        </div>
    </footer>




</body>

</html>