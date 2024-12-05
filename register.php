<?php
require('functions.inc.php');

requiredLoggedOut();


$pageTitle = "Register";
$errors = [];

$firstname = "";
$lastname = "";
$mail = "";
$password = "";
$optin = 0;

if (isset($_POST['submit'])) {
    //first name validatie
    if (!isset($_POST['inputfirstname'])) {
        $errors[] = "First name is required.";
    } else {
        $firstname = $_POST['inputfirstname'];

        if (strlen($firstname) < 1) {
            $errors[] = "First name is required.";
        }
        if (preg_match("/[^a-zA-Z\s'-]/", $firstname)) {
            $errors[] = "First name can not contain special characters";
        }
    }

    //last name validatie
    if (!isset($_POST['inputlastname'])) {
        $errors[] = "Last name is required.";
    } else {
        $lastname = $_POST['inputlastname'];

        if (strlen($lastname) < 1) {
            $errors[] = "Last name is required.";
        }

        if (preg_match("/[^a-zA-Z\s'-]/", $lastname)) {
            $errors[] = "First name can not contain special characters";
        }
    }

    //mail validatie
    if (!isset($_POST['inputmail'])) {
        $errors[] = "E-mail is required.";
    } else {
        $mail = $_POST['inputmail'];
        if (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "E-mail address is invalid.";
        }
        if (existingMail($mail) == true) {
            $errors[] = "Mail already exists.";
        }
    }

    //password validatie
    if (!isset($_POST['inputpass'])) {
        $errors[] = "Password is required.";
    } else {
        $password = $_POST['inputpass'];
        if (!preg_match("/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/", $password)) {
            $errors[] = "Password needs to contain at least 1 uppercase letter, 1 lowercase, 1 symbol, 1 number and needs to be at least 8 characters long.";
        }
    }

    //optin validatie
    if (isset($_POST['inputoptin'])) {
        $optin = 1;
    }

    if (count($errors) == 0) { // er werden geen fouten geregistreerd tijdens validatie
        $newId = registerNewMember($firstname, $lastname, $mail, $password, $optin);
        if (!$newId) {
            $errors[] = "An unknown error has occured, pleace contact us...";
        } else {
            setLogin();
            $_SESSION['message'] = "Welcome $firstname!";
            header("Location: admin.php");
            exit;
        }
    }
}

// print '<pre>';
// print_r($errors);
// print '</pre>';

require('head.inc.php');
?>

<body>
    <!-- Section: Design Block -->
    <section class="vh-100 background-radial-gradient overflow-scroll">
        <style>
            .background-radial-gradient {
                background-color: hsl(218, 41%, 15%);
                background-image: radial-gradient(650px circle at 0% 0%,
                        hsl(218, 41%, 35%) 15%,
                        hsl(218, 41%, 30%) 35%,
                        hsl(218, 41%, 20%) 75%,
                        hsl(218, 41%, 19%) 80%,
                        transparent 100%),
                    radial-gradient(1250px circle at 100% 100%,
                        hsl(218, 41%, 45%) 15%,
                        hsl(218, 41%, 30%) 35%,
                        hsl(218, 41%, 20%) 75%,
                        hsl(218, 41%, 19%) 80%,
                        transparent 100%);
            }

            #radius-shape-1 {
                height: 220px;
                width: 220px;
                top: -60px;
                left: -130px;
                background: radial-gradient(#44006b, #ad1fff);
                overflow: hidden;
            }

            #radius-shape-2 {
                border-radius: 38% 62% 63% 37% / 70% 33% 67% 30%;
                bottom: -60px;
                right: -110px;
                width: 300px;
                height: 300px;
                background: radial-gradient(#44006b, #ad1fff);
                overflow: hidden;
            }

            .bg-glass {
                background-color: hsla(0, 0%, 100%, 0.9) !important;
                backdrop-filter: saturate(200%) blur(25px);
            }
        </style>
        <div class="container px-4 py-5 px-md-5 text-center text-lg-start my-5">
            <div class="row gx-lg-5 align-items-center mb-5">
                <div class="col-lg-6 mb-5 mb-lg-0" style="z-index: 10">
                    <h1 class="my-5 display-5 fw-bold ls-tight" style="color: hsl(218, 81%, 95%)">
                        Learning PHP <br />
                        <span style="color: hsl(218, 81%, 75%)">login / register / admin panel</span>
                    </h1>
                    <p class="mb-4 opacity-70" style="color: hsl(218, 81%, 85%)">
                        This is me learning how to make a register page with validation.
                    </p>
                </div>

                <div class="col-lg-6 mb-5 mb-lg-0 position-relative">
                    <div id="radius-shape-1" class="position-absolute rounded-circle shadow-5-strong"></div>
                    <div id="radius-shape-2" class="position-absolute shadow-5-strong"></div>

                    <div class="card bg-glass">
                        <div class="card-body px-4 py-5 px-md-5">
                            <?php if (count($errors)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="register.php">
                                <!-- 2 column grid layout with text inputs for the first and last names -->
                                <div class="row">
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <input type="text" id="inputfirstname" name="inputfirstname" class="form-control" value="<?= $firstname; ?>" />
                                            <label class="form-label" for="inputfirstname">First name</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 mb-4">
                                        <div data-mdb-input-init class="form-outline">
                                            <input type="text" id="inputlastname" name="inputlastname" class="form-control" value="<?= $lastname; ?>" />
                                            <label class="form-label" for="inputlastname">Last name</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Email input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="email" id="inputmail" name="inputmail" class="form-control" value="<?= $mail; ?>" />
                                    <label class="form-label" for="inputmail">Email address</label>
                                </div>

                                <!-- Password input -->
                                <div data-mdb-input-init class="form-outline mb-4">
                                    <input type="password" id="inputpass" name="inputpass" class="form-control" value="<?= $password; ?>" />
                                    <label class="form-label" for="inputpass">Password</label>
                                </div>

                                <!-- Checkbox -->
                                <div class="form-check d-flex justify-content-center mb-4">
                                    <input class="form-check-input me-2" type="checkbox" value="1" name="inputoptin" id="inputoptin" />
                                    <label class="form-check-label" for="inputoptin">
                                        Subscribe to our newsletter
                                    </label>
                                </div>

                                <!-- Submit button -->
                                <button type="submit" value="submit" name="submit" data-mdb-button-init data-mdb-ripple-init class="btn btn-primary btn-block mb-4">
                                    Sign up
                                </button>
                                <div>
                                    <p class="mb-0">Already have an account? <a href="login.php" class="fw-bold" style="color: hsl(218, 81%, 75%)">Login</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Section: Design Block -->
</body>

</html>