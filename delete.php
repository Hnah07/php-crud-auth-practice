<?php
require('functions.inc.php');
requiredLoggedIn();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];
    if ($id > 0) {
        deleteArticle($id);
        header("Location: admin.php?message=Article $id has been deleted.");
        exit;
    }
}
header("Location: admin.php?message=Invalid article ID.");
exit;
