<?php
$isAdmin = false;
$isUser = false;

if (isset($_GET['signOut']) && $_GET['signOut'] == 'true') {
    $_SESSION = [];
}

if (isset($_SESSION['login'])) {
    $queryHeader = "SELECT * FROM user WHERE username = :username";
    $statementHeader = $db->prepare($queryHeader);
    $statementHeader->bindValue(':username', $_SESSION['login']);
    $statementHeader->execute();

    $user = $statementHeader->fetch();

    $_SESSION['admin'] = $user['user_admin'];
    $_SESSION['id'] = $user['user_id'];

    if ($user['user_admin'] == 1) {
        $isAdmin = true;
    } else if ($user['user_admin'] == 0) {
        $isUser = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://getbootstrap.com/docs/5.2/assets/css/docs.css" rel="stylesheet">
    <title><?= $titletag ?></title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark py-3">
            <div class="container-fluid col-11">
                <a class="navbar-brand" href="index.php"><?= $sitename ?></a>
                <div class="collapse navbar-collapse" id="navbarText">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="blog.php">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
                        </li>
                        <?php if (!isset($_SESSION['login'])) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="register.php">Register</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="login.php">Login</a>
                            </li>
                        <?php else : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="#"><?= $_SESSION['login'] ?></a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="?signOut=true">Sign Out</a>
                            </li>
                        <?php endif ?>
                        <?php if ($isAdmin) : ?>
                            <li class="nav-item">
                                <a class="nav-link" href="admin.php">Admin</a>
                            </li>
                        <?php endif ?>
                    </ul>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>
        </nav>
    </header>