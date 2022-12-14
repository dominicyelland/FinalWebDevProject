<?php
require('connect.php');

$invalidData = false;
$incorrectInfo = false;
$username = filter_input(INPUT_POST, 'post_username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$password = filter_input(INPUT_POST, 'post_password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valid = !empty($username) && !empty($password) && strlen(trim($username)) > 0 && strlen(trim($password)) > 0 ? true : false;

if ($valid) {
    $query = "SELECT * FROM user WHERE username = :post_username AND user_password = :post_password";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_username', $username);
    $statement->bindValue(':post_password', $password);
    $statement->execute();

    $account = $statement->fetch();

    if (!empty($account)) {
        $_SESSION['login'] = $account['username'];
        header("location:blog.php");
        exit();
    } else {
        $incorrectInfo = true;
    }
} else if (!$valid && isset($_POST['post_username']) && isset($_POST['post_password'])) {
    $invalidData = true;
}

$pagename = "Login";
$sitename = "Dominic's Porfolio Website";
$titletag = $pagename . " - " . $sitename;
require_once("header.php");
?>
<main>
    <form class="row g-3" action="login.php" method="post">
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
        <div class="col-12">
            <button class="btn btn-primary" type="submit" name="command" value="Login">Login</button>
        </div>
        <?php if ($invalidData) : ?>
            <p>Please enter data!</p>
        <?php endif ?>
        <?php if ($incorrectInfo) : ?>
            <p>Username or password is incorrect.</p>
        <?php endif ?>
    </form>
</main>
<?php require_once("footer.php"); ?>