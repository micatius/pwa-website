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
        <section class="category">
            <div class="section-title">
                <div class="checkerboard"></div>
                <h2>Mundo</h2>
            </div>
            <div class="articles">
                <article>
                    <div class="thumbnail">
                        <img src="img/thumbnails/flowers-thumb.png" alt="thumbnail">
                    </div>
                    <div class="article-info">
                        <span class="topic-label">ESTADOS UNIDOS</span>
                        <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                        <span class="author">POR ANDRES RODRIGUEZ</span>
                        <time class="timestamp">HACE 31 MINUTOS</time>
                    </div>

                </article>

                <article>
                    <div class="thumbnail">
                        <img src="img/thumbnails/obesity-thumb.png" alt="thumbnail">
                    </div>
                    <header>
                        <span class="topic-label">ESTADOS UNIDOS</span>
                        <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                    </header>

                    <footer class="meta-info">
                        <span class="author">POR ANDRES RODRIGUEZ</span>
                        <time class="timestamp">HACE 31 MINUTOS</time>
                    </footer>
                </article>

                <article>
                    <div class="thumbnail">
                        <img src="img/thumbnails/plane-thumb.png" alt="thumbnail">
                    </div>
                    <header>
                        <span class="topic-label">ESTADOS UNIDOS</span>
                        <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                    </header>

                    <footer class="meta-info">
                        <span class="author">POR ANDRES RODRIGUEZ</span>
                        <time class="timestamp">HACE 31 MINUTOS</time>
                    </footer>
                </article>

                <article>
                    <div class="thumbnail">
                        <img src="img/thumbnails/tornado-thumb.png" alt="thumbnail">
                    </div>
                    <header>
                        <span class="topic-label">ESTADOS UNIDOS</span>
                        <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                    </header>

                    <footer class="meta-info">
                        <span class="author">POR ANDRES RODRIGUEZ</span>
                        <time class="timestamp">HACE 31 MINUTOS</time>
                    </footer>
                </article>
            </div>
            <hr>
        </section>
        <section class="category">
            <div class="section-title">
                <div class="checkerboard"></div>
                <h2>Deporte</h2>
            </div>
                <div class="articles">
                    <article>
                        <div class="thumbnail">
                            <img src="img/thumbnails/flowers-thumb.png" alt="thumbnail">
                        </div>
                        <header>
                            <span class="topic-label">ESTADOS UNIDOS</span>
                            <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                        </header>

                        <footer class="meta-info">
                            <span class="author">POR ANDRES RODRIGUEZ</span>
                            <time class="timestamp">HACE 31 MINUTOS</time>
                        </footer>
                    </article>

                    <article>
                        <div class="thumbnail">
                            <img src="img/thumbnails/obesity-thumb.png" alt="thumbnail">
                        </div>
                        <header>
                            <span class="topic-label">ESTADOS UNIDOS</span>
                            <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                        </header>

                        <footer class="meta-info">
                            <span class="author">POR ANDRES RODRIGUEZ</span>
                            <time class="timestamp">HACE 31 MINUTOS</time>
                        </footer>
                    </article>

                    <article>
                        <div class="thumbnail">
                            <img src="img/thumbnails/plane-thumb.png" alt="thumbnail">
                        </div>
                        <header>
                            <span class="topic-label">ESTADOS UNIDOS</span>
                            <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                        </header>

                        <footer class="meta-info">
                            <span class="author">POR ANDRES RODRIGUEZ</span>
                            <time class="timestamp">HACE 31 MINUTOS</time>
                        </footer>
                    </article>

                    <article>
                        <div class="thumbnail">
                            <img src="img/thumbnails/tornado-thumb.png" alt="thumbnail">
                        </div>
                        <header>
                            <span class="topic-label">ESTADOS UNIDOS</span>
                            <h3 class="headline">Tornados dejan daños en casas del sur de Estados Unidos</h2>
                        </header>

                        <footer class="meta-info">
                            <span class="author">POR ANDRES RODRIGUEZ</span>
                            <time class="timestamp">HACE 31 MINUTOS</time>
                        </footer>
                    </article>
                </div>
            
            <hr>
        </section>
    </main>

    <footer class="page-footer">
        <div class="copyright">
            <p>© Copyright EL DEBATE. Todos los derechos reservados.</p>
        </div>
    </footer>




</body>

</html>