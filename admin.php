<?php
require('functions.inc.php');
$pageTitle = "admin";
requiredLoggedIn();

$sort = 'title';
$direction = 'ASC';

if (in_array(@$_GET['sort'], ['id', 'title', 'firstname', 'publication_date', 'status'])) {
    $sort = $_GET['sort'];
}

if (in_array(@$_GET['dir'], ['down'])) {
    $direction = 'DESC';
}

$articles = getArticles();
$articles = sortArticles($sort, $direction);

$itemsPerPage = 10;
$start = 0;

$totalAmountOfArticles = count($articles);
$totalAmountOfPages = ceil($totalAmountOfArticles / $itemsPerPage);

$currentPage = @$_GET['page'];
$currentPage = (int)$currentPage;

if ($currentPage == 0 || $currentPage > $totalAmountOfPages) {
    $currentPage = 1;
}

$start = (($currentPage - 1) * $itemsPerPage);

$stop = $start + $itemsPerPage;
if ($stop > $totalAmountOfArticles) {
    $stop = $totalAmountOfArticles;
}

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
    <h2 class="text-center">Total amount of posts: <?= $totalAmountOfArticles; ?></h2>
    <h2 class="text-center">Total amount of pages: <?= $totalAmountOfPages; ?></h2>
    <h1 class="mt-5">Articles overview</h1>

    <?php if (isset($_GET["message"])): // zit er een message in mijn GET array? 
    ?>
        <div class="p-3 text-success-emphasis bg-success-subtle border border-success-subtle rounded-3">
            <?= $_GET["message"]; ?>
        </div>
    <?php endif; ?>
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
            <?php
            $articles = array_slice($articles, $start, $itemsPerPage);
            foreach ($articles as $article): ?>
                <tr>
                    <th scope="row"><?= $article['id']; ?></th>
                    <td><?= mb_strimwidth($article['title'], 0, 25, "..."); ?></td>
                    <td><?= $article['firstname'] . " " . $article['lastname']; ?></td>
                    <td><?= date('j M Y', strtotime($article['publication_date'])); ?></td>
                    <td><button class="<?= $article['status'] ? 'btn btn-success' : 'btn btn-danger'; ?>" style="cursor: default;"><?= $article['status'] ? 'published' : 'unpublished'; ?></button></td>
                    <td>
                        <a href="#">
                            <button type="button" class="btn btn-outline-primary">View</button></a>
                        <a href="editform.php?id=<?= $article['id']; ?>">
                            <button type="button" class="btn btn-outline-warning">Edit</button></a>
                        <!-- DELETE ARTICLE -->
                        <form method="post" action="delete.php" style="display: inline;">
                            <input type="hidden" name="id" value="<?= $article['id']; ?>">
                            <button type="submit" class="btn btn-outline-danger"
                                onclick="return confirm('Are you sure you want to delete this article?');">
                                Delete
                            </button>
                        </form>


                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end" style="margin-right: 3rem;">
    <ul class="pagination">
        <?php if ($currentPage > 1): ?>
            <li class="page-item">
                <a class="page-link" href="admin.php?page=<?= $currentPage - 1 ?>&sort=<?= $sort ?>&dir=<?= $direction ?>" aria-label=" Previous">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
        <?php endif; ?>
        <!-- <li class="page-item"><a class="page-link" href="#">1</a></li>
        <li class="page-item"><a class="page-link" href="#">2</a></li>
        <li class="page-item"><a class="page-link" href="#">3</a></li> -->
        <?php if ($currentPage < $totalAmountOfPages): ?>
            <li class="page-item">
                <a class="page-link" href="admin.php?page=<?= $currentPage + 1 ?>&sort=<?= $sort ?>&dir=<?= $direction ?>" aria-label=" Next">
                    <span class="sr-only">Next</span>
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</div>