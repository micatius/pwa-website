    <header class="page-header">
        <div class="logo"> <a href="index.php"><?php include 'components/debate-logo.svg.html'; ?> </a></div>
        <nav>
            <ul>
                <li><a href="index.php" class="<?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>">HOME</a></li>
                <li><a href="mundo.php" class="<?= basename($_SERVER['PHP_SELF']) == 'mundo.php' ? 'active' : '' ?>">MUNDO</a></li>
                <li><a href="deporte.php" class="<?= basename($_SERVER['PHP_SELF']) == 'deporte.php' ? 'active' : '' ?>">DEPORTE</a></li>
                <li><a href="admin.php" class="<?= basename($_SERVER['PHP_SELF']) == 'admin.php' ? 'active' : '' ?>">ADMINISTRACIJA</a></li>
            </ul>
        </nav>
    </header>