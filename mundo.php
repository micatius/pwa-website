<?php
    include 'scripts/fetch_articles.php'
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Debate - Mundo</title>
    <link rel="stylesheet" href="style.css?v=<?= time(); ?>">
</head>

<body>
    <?php include 'components/header.php'; ?>

    <main>
        <section class="category">
            <div class="section-title">
                <div class="checkerboard"></div>
                <h2>Mundo</h2>
            </div>
            <div class="articles">
                <?php
                    renderArticlesByCategory(1, 8)
                ?>
            </div>
            <hr>
    </main>

    <?php include 'components/footer.php'; ?>

</body>

</html>