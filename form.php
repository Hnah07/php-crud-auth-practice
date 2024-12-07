<?php
require('functions.inc.php');
requiredLoggedIn();

$pageTitle = "Add an article";
$users = getUsers();

$errors = [];
$submitted = false;

// Kijken of het formulier ge-submit werd
if (@$_POST['submit']) { // is "submit" als key aanwezig in de $_POST array

    $submitted = true;



    // 1: alle default values declareren
    $title = "";
    $body = "";
    $user_id = null;
    $status = 0;
    $datum = null;

    // 2: validatie uitvoeren
    if (!isset($_POST['title'])) { // zit title in mijn POST?
        $errors[] = "Title field missing...";
    } else {
        if (strlen($_POST['title']) == 0) { // is het title field ingevuld?
            $errors[] = "Title field can not be empty";
        } else { // Er waren geen problemen met de waarde van title
            $title = $_POST['title'];
        }
    }

    if (!isset($_POST['body'])) { // zit body in mijn POST?
        $errors[] = "Body field missing...";
    } else {
        $body = $_POST['body'];
    }

    if (!isset($_POST['user'])) { // zit user in mijn POST?
        $errors[] = "User field missing...";
    } else {
        if ((int)$_POST['user'] === 0) {
            $_POST['user'] = null;
        }

        if (!isset($users[$_POST['user']]) && $_POST['user'] == null) { // is user id niet geldig?
            $errors[] = "User ID is not valid.";
        } else {
            $user_id = $_POST['user'];
        }
    }

    if (isset($_POST['status']) && (int)$_POST['status'] === 1) { // zit status in mijn POST én is deze gelijk aan 1 (niet aangevinkte checkboxes worden niet via post meegestuurd)?
        $status = 1;
    }

    if (isset($_POST['datum']) && !empty($_POST['datum'])) {
        $datum = $_POST['datum'] . ' 00:00:00';
    } else {
        $errors[] = "Select publication date.";
    }
    // 3: indien validatie ok: insert into db
    if (count($errors) == 0) { // er werden geen fouten geregistreerd tijdens validatie
        $return = insertArticleItem($title, $body, $user_id, $status, $datum);
        header("Location: admin.php?message=Record werd toegevoegd...");
        exit;
    }
}

// print '<pre>';
// print_r($_POST);
// print '</pre>';

require('head.inc.php');
?>

<body>
    <div class="container">
        <main class="col-md-9">
            <h2>Add new item</h2>

            <?php if (count($errors) > 0): ?>
                <div class="p-3 text-warning-emphasis bg-warning-subtle border border-warning-subtle rounded-3">
                    <ul>
                        <?php foreach ($errors as $error): ?>
                            <li><?= $error; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <?php if ($submitted && count($errors) == 0): ?>
                <div class="p-3 text-success-emphasis bg-success-subtle border border-success-subtle rounded-3">
                    Nieuws item werd toegevoegd...
                </div>
            <?php endif; ?>


            <form method="post" action="form.php">

                <div class="mb-3">
                    <label for="title" class="form-label">Title *</label>
                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter title..." value="<?= @$title; ?>" />
                </div>
                <div class="mb-3">
                    <label for="body" class="form-label">Article</label>
                    <textarea class="form-control" id="body" name="body" rows="5"><?= @$body; ?></textarea>
                </div>

                <div class="mb-3">
                    <select id="user" name="user" class="form-select" aria-label="User">
                        <option <?= @$user_id == null ? 'selected' : ''; ?> value="0">Please select a user</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id']; ?>" <?= $user['id'] == @$user_id ? 'selected' : ''; ?>><?= $user['firstname'] . " " .  $user['lastname']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" role="switch" id="status" name="status" value="1" <?= $submitted && $status == 0 ? '' : 'checked'; ?>>
                        <label class="form-check-label" for="status">Published</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <label for="startDate">Publication date</label>
                        <input id="startDate" name="datum" class="form-control" type="date" />
                        <span id="startDateSelected"></span>
                    </div>
                </div>

                <button class="btn btn-primary" type="submit" name="submit" id="submit" value="submit" />Submit</button>
            </form>

        </main>

    </div>


</body>

</html>