<?php
require('functions.inc.php');
requiredLoggedIn();

$users = getUsers();

// Haal het artikel-ID op uit de URL
$id = (int)$_GET['id'] ?? 0;

// Controleer of het ID geldig is
if ($id <= 0) {
    header("Location: admin.php?message=Invalid article ID.");
    exit;
}

// Haal het artikel op uit de database
$article = getArticleById($id);

// Controleer of het artikel bestaat
if (!isset($article)) {
    header("Location: admin.php?message=Article not found.");
    exit;
}

foreach ($users as $user) {
    if ($user['id'] == $article['user_id']) {
        break;
    }
}

$pageTitle = "View article";
require('head.inc.php');
?>

<body>
    <div class="container py-5">
        <h1><?= $article['title']; ?></h1>

        <p><strong>User:</strong> <?= $user['firstname'] . ' ' . $user['lastname']; ?></p>

        <p><strong>Publication Date:</strong> <?= date('j M Y', strtotime($article['publication_date'])); ?></p>
        <p><strong>Status:</strong> <?= $article['status'] ? 'Published' : 'Unpublished'; ?></p>
        <hr>
        <div>
            <?= $article['body']; ?>
        </div>
        <a href="admin.php" class="btn btn-primary mt-4">Back to overview</a>
    </div>
</body>

</html>