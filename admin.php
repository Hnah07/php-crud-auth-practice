<?php
require('functions.inc.php');
$pageTitle = "admin";
requiredLoggedIn();



$articles = getArticles();

require('head.inc.php');
?>
<header class="text-end m-5">
    <a href="logout.php" button" class="btn btn-outline-danger btn-lg">Log out</a>
</header>
<div class="container py-5 h-100">
    <!-- TODO : welcome $firstname -->
    <h1>Articles overview</h1>
    <!-- TODO a to form.php -->
    <a href="#"><button type="button" class="btn btn-outline-primary"><i class="bi bi-plus-circle"></i> Add new item</button></a>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">User</th>
                <th scope="col">Publication date</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($articles as $article): ?>
                <tr>
                    <th scope="row"><?= $article['id']; ?></th>
                    <td><?= $article['title']; ?></td>
                    <td><?= $article['firstname'] . " " . $article['lastname']; ?></td>
                    <td><?= $article['publication_date']; ?></td>
                    <td><button class="<?= $article['status'] ? 'btn btn-success' : 'btn btn-danger'; ?>" style="cursor: default;"><?= $article['status'] ? 'published' : 'unpublished'; ?></button></td>
                    <td>
                        <a href="index.php">
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