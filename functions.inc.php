<?php
function connectToDB()
{
    // CONNECTIE CREDENTIALS
    $db_host = '127.0.0.1';
    $db_user = 'root';
    $db_password = 'root';
    $db_db = 'oefmix';
    $db_port = 8889;

    try {
        $db = new PDO('mysql:host=' . $db_host . '; port=' . $db_port . '; dbname=' . $db_db, $db_user, $db_password);
    } catch (PDOException $e) {
        echo "Error!: " . $e->getMessage() . "<br />";
        die();
    }
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, FALSE);
    return $db;
}

function registerNewMember(String $firstname, String $lastname, String $mail, String $password, int $optin): bool|int
{
    $db = connectToDB();
    $sql = "INSERT INTO users(firstname, lastname, mail, password, optin) VALUES (:firstname, :lastname, :mail, :password, :optin)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':firstname' => $firstname,
        ':lastname' => $lastname,
        ':mail' => $mail,
        ':password' => md5($password),
        ':optin' => $optin
    ]);
    return $db->lastInsertId();
}


function existingMail(String $mail): bool
{
    $sql = "SELECT mail FROM users WHERE mail = :mail";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([':mail' => $mail]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function isLoggedIn(): bool
{
    session_start();

    $loggedin = FALSE;

    if (isset($_SESSION['loggedin'])) {
        if ($_SESSION['loggedin'] > time()) {
            $loggedin = TRUE;
            setLogin();
        }
    }

    return $loggedin;
}

function requiredLoggedIn()
{
    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}

function requiredLoggedOut()
{
    if (isLoggedIn()) {
        header("Location: admin.php");
        exit;
    }
}

function setLogin($uid = false)
{
    $_SESSION['loggedin'] = time() + 3600;

    if ($uid) {
        $_SESSION['uid'] = $uid;
    }
}

function isValidLogin(String $mail, String $pass): bool
{
    $sql = "SELECT id FROM users WHERE mail=:mail AND password=:password AND status = 1";

    $stmt = connectToDB()->prepare($sql);
    $stmt->execute([
        ':mail' => $mail,
        ':password' => md5($pass)
    ]);
    return $stmt->fetch(PDO::FETCH_COLUMN);
}

function getArticles()
{
    $sql = "SELECT articles.*, users.firstname, users.lastname
        FROM articles
        LEFT JOIN users on articles.user_id = users.id";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function welcomeMessage()
{
    if (isset($_SESSION['message'])) {
        return $_SESSION['message'];
    }
}

function getUsers(): array
{
    $sql = "SELECT * FROM users ORDER BY firstname ASC";
    $stmt = connectToDB()->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function insertArticleItem(String $title, String $body, int $user_id = null, int $status = 1, String $publication_date = null): bool|int
{
    $db = connectToDB();
    $sql = "INSERT INTO articles(title, body, user_id, status, publication_date) VALUES (:title, :body, :user_id, :status, :publication_date)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':body' => $body,
        ':user_id' => $user_id,
        ':status' => $status,
        ':publication_date' => $publication_date
    ]);
    return $db->lastInsertId();
}
