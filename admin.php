<?php
require('functions.inc.php');
$pageTitle = "admin";
requiredLoggedIn();
$articles = getArticles();

$sort = 'title';
$direction = 'ASC';

if (in_array(@$_GET['sort'], ['id', 'title', 'firstname', 'publication_date', 'status'])) {
    $sort = $_GET['sort'];
}

if (in_array(@$_GET['dir'], ['down'])) {
    $direction = 'DESC';
}

$articles = sortArticles($sort, $direction);

// print '<pre>';
// var_dump($sortArticles);
// print '</pre>';


require('head.inc.php');
?>
<style>
    a {
        text-decoration: none;
        color: inherit;

        &:hover {
            color: blue;
        }
    }
</style>
<header class="text-end m-5">
    <a href="logout.php" button" class="btn btn-outline-danger btn-lg">Log out</a>
</header>
<div class="container py-5 h-100">
    <!-- TODO : welcome $firstname -->
    <h1>Articles overview</h1>
    <!-- TODO a to form.php -->
    <a href="form.php"><button type="button" class="btn btn-outline-primary"><i class="bi bi-plus-circle"></i> Add new item</button></a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col"><a href="?sort=id&dir=<?= ($sort == 'id' && $direction == 'ASC' ? 'down' : 'up'); ?>"># <i class="bi bi-arrow-down-up"></i></a></th>
                <th scope="col"><a href="?sort=title&dir=<?= ($sort == 'title' && $direction == 'ASC' ? 'down' : 'up'); ?>">Title <i class="bi bi-arrow-down-up"></i></a></th>
                <th scope="col"><a href="?sort=firstname&dir=<?= ($sort == 'firstname' && $direction == 'ASC' ? 'down' : 'up'); ?>">User <i class="bi bi-arrow-down-up"></i></a></th>
                <th scope="col"><a href="?sort=publication_date&dir=<?= ($sort == 'publication_date' && $direction == 'ASC' ? 'down' : 'up'); ?>">Publication date <i class="bi bi-arrow-down-up"></i></a></th>
                <th scope="col"><a href="?sort=status&dir=<?= ($sort == 'status' && $direction == 'ASC' ? 'down' : 'up'); ?>">Status <i class="bi bi-arrow-down-up"></i></a></th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <th scope="row"><?= $article['id']; ?></th>
                    <td><?= mb_strimwidth($article['title'], 0, 25, "..."); ?></td>
                    <td><?= $article['firstname'] . " " . $article['lastname']; ?></td>
                    <td><?= date('j M Y', strtotime($article['publication_date'])); ?></td>
                    <td><button class="<?= $article['status'] ? 'btn btn-success' : 'btn btn-danger'; ?>" style="cursor: default;"><?= $article['status'] ? 'published' : 'unpublished'; ?></button></td>
                    <td>
                        <a href="#">
                            <button type="button" class="btn btn-outline-primary">View</button></a>
                        <a href="#">
                            <button type="button" class="btn btn-outline-warning">Edit</button></a>
                        <a href="#">
                            <button type="button" class="btn btn-outline-danger">Delete</button></a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
</div>