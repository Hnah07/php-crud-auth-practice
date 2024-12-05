<?php
require('functions.inc.php');

requiredLoggedOut();

$pageTitle = "Login";
$errors = [];

// Werd form ge-submit?
if (isset($_POST['inputmail'])) {

    // eerst validatie op mail (low level)
    if (!strlen($_POST['inputmail'])) {
        $errors[] = "Please fill in e-mail.";
    }

    // validatie op password (low level)
    if (!strlen($_POST['inputpass'])) {
        $errors[] = "Please fill in password.";
    }

    if ($uid = isValidLogin($_POST['inputmail'], $_POST['inputpass'])) {
        // login success

        setLogin(); //ik wil user id ingeven

        header("Location: admin.php");
        exit;
    } else {
        $errors[] = "E-mail and/or password is not correct.";
    }
}
print '<pre>';
print_r($errors);
print '</pre>';

require('head.inc.php');
?>
<section class="vh-100 gradient-custom">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card bg-dark text-white" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">

                        <div class="mb-md-5 mt-md-4 pb-5">

                            <h2 class="fw-bold mb-2 text-uppercase">Login</h2>
                            <p class="text-white-50 mb-5">Please enter your login and password!</p>
                            <?php if (count($errors)): ?>
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        <?php foreach ($errors as $error): ?>
                                            <li><?= $error; ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>
                            <form method="post" action="login.php">
                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <input type="email" id="inputmail" name="inputmail" class="form-control form-control-lg" />
                                    <label class="form-label" for="inputmail">Email</label>
                                </div>

                                <div data-mdb-input-init class="form-outline form-white mb-4">
                                    <input type="password" id="inputpass" name="inputpass" class="form-control form-control-lg" />
                                    <label class="form-label" for="inputpass">Password</label>
                                </div>

                                <p class="small mb-5 pb-lg-2"><a class="text-white-50" href="#!">Forgot password?</a></p>

                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="submit" value="submit" name="submit">Login</button>
                        </div>
                        </form>

                        <div>
                            <p class="mb-0">Don't have an account? <a href="register.php" class="text-white-50 fw-bold">Sign Up</a>
                            </p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>