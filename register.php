<?php
require('connect.php');

function filteremail()
{
    if (filter_input(INPUT_POST, 'post_email', FILTER_VALIDATE_EMAIL) && filter_input(INPUT_POST, 'post_email', FILTER_SANITIZE_EMAIL)) {
        return $_POST['post_email'];
    }
}

function filterpassword()
{
    if (filter_input(INPUT_POST, 'post_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS) && filter_input(INPUT_POST, 'post_passwordConfirm', FILTER_SANITIZE_FULL_SPECIAL_CHARS)) {
        if (strcmp($_POST['post_password'], $_POST['post_passwordConfirm']) === 0) {
            return $_POST['post_password'];
        }
    }
}

$username = filter_input(INPUT_POST, 'post_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filterpassword();
$email = filteremail();
$valid = !empty($username) && !empty($password) && !empty($email) && strlen(trim($username)) > 0 && strlen(trim($password)) > 0 && strlen(trim($email)) > 0 ? true : false;
$repeatData = false;
$invalidData = false;

if ($valid) {
    $query = "SELECT username, user_email FROM user WHERE username = :post_username OR user_email = :post_email";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_username', $username);
    $statement->bindValue(':post_email', $email);
    $statement->execute();

    if (empty($statement->fetch())) {
        $query = "INSERT INTO user (username, user_password, user_email) VALUES (:post_username, :post_password, :post_email)";
        $statement = $db->prepare($query);
        $statement->bindValue(':post_username', $username);
        $statement->bindValue(':post_password', $password);
        $statement->bindValue(':post_email', $email);
        $statement->execute();

        $_SESSION['login'] = $username;
        header("location:blog.php");
        exit();
    } else {
        $repeatData = true;
    }
} else if (!$valid && isset($_POST['post_username']) && isset($_POST['post_password']) && isset($_POST['post_email'])) {
    $invalidData = true;
}

$pagename = "Registration";
$sitename = "Dominic's Porfolio Website";
$titletag = $pagename . " - " . $sitename;
require_once("header.php");
?>
<main>
    <form class="row g-3" action="register.php" method="post">
        <div class="col-md-4">
            <label for="post_username" class="form-label">Username</label>
            <div class="input-group">
                <span class="input-group-text" id="inputGroupPrepend2">@</span>
                <input type="text" class="form-control" name="post_username" id="post_username" aria-describedby="inputGroupPrepend2" required>
            </div>
        </div>
        <div class="col-md-6">
            <label for="post_password" class="form-label">Password</label>
            <input type="password" class="form-control" name="post_password" id="post_password" required>
        </div>
        <div class="col-md-6">
            <label for="post_passwordConfirm" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" name="post_passwordConfirm" id="post_passwordConfirm" required>
        </div>
        <div class="col-md-6">
            <label for="post_email" class="form-label">Email</label>
            <input type="email" class="form-control" name="post_email" id="post_email" required>
        </div>
        <div class="col-12">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="" id="invalidCheck2" required>
                <label class="form-check-label" for="invalidCheck2">
                    Agree to terms and conditions
                </label>
            </div>
        </div>
        <div class="col-12">
            <button class="btn btn-primary" type="submit" name="command" value="Register">Register Account</button>
        </div>
        <?php if ($repeatData) : ?>
            <p>This username or email address is already in use.</p>
        <?php endif ?>
        <?php if ($invalidData) : ?>
            <p>Please enter data!</p>
        <?php endif ?>
        <?php if (!filterpassword() && isset($_POST['post_password']) && isset($_POST['post_passwordConfirm'])) : ?>
            <p>Passwords do not match!</p>
        <?php endif ?>
    </form>
</main>
<?php require_once("footer.php"); ?>