<?php
include 'article_processing.php'
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
        <?php if (isset($_SESSION['level']) && $_SESSION['level'] == 0): ?>
            <p style="color: red; margin: 2em 0; text-align: center;">
                <?php
                    echo "Dobrodošli, " . $_SESSION['first_name'] . ' ' . $_SESSION['last_name'] . '.';
                ?>
                Nemate administratorske ovlasti za uređivanje članaka.
            </p>
        <?php else: ?>
            <form action="" method="post" enctype="multipart/form-data">
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

            <form action="" method="post" enctype="multipart/form-data" class="article-form">
                <input type="hidden" name="id" value="<?= htmlspecialchars($selectedArticle ?? '') ?>">

                <label for="title">Naslov:</label>
                <input type="text" name="title" id="title" value="<?= htmlspecialchars($articleData['title']) ?>">

                <label for="keyword">Ključna riječ:</label>
                <input type="text" name="keyword" id="keyword" value="<?= htmlspecialchars($articleData['keyword']) ?>">

                <label for="content">Sadržaj:</label>
                <textarea name="content" id="content" class="full-width"><?= htmlspecialchars($articleData['content']) ?></textarea>

                <label for="image">Slika članka:</label>
                <input type="file" name="image" id="image">
                <?php if (!empty($articleData['image'])): ?>
                    <div></div>
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

                <label for="archive">Arhiviraj članak:</label>
                <input type="checkbox" name="archive" id="archive" <?= $articleData['archived'] ? 'checked' : '' ?>>

                <div></div> <!-- Empty cell to align buttons -->
                <div class="buttons">
                    <button type="submit">Spremi članak</button>
                    <?php if ($selectedArticle): ?>
                        <button type="submit" name="delete" value="1" onclick="return confirm('Jeste li sigurni da želite obrisati članak?')">Obriši članak</button>
                    <?php endif; ?>
                </div>
            </form>

            <!-- Errors and Success below the form -->
            <?php if ($errors): ?>
                <div class="errors" style="color:red; margin-top:1em;">
                    <ul>
                        <?php foreach ($errors as $e): ?>
                            <li><?= htmlspecialchars($e) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php elseif ($success): ?>
                <div class="success" style="color:green; margin-top:1em;">
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form class="logout-form" action="logout.php" method="post" class="logout-form">
                    <button class="logout-button" type="submit">Odjava</button>
                </form>
            <?php endif; ?>
        


    </main>

    <?php include 'components/footer.php'; ?>
</body>

</html>