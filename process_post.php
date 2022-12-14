<?php
require('connect.php');

$title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$valid = !empty($title) && !empty($content) && strlen($title) > 0 && strlen($content) > 0 ? true : false;

$comment = filter_input(INPUT_POST, 'post_comment', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$validComment = !empty($comment) && strlen($comment) > 0 ? true : false;

if (isset($_POST['command']) && $_POST['command'] === "Delete") {
    $query = "DELETE FROM post WHERE post_id = :post_id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statement->execute();
    header("location:blog.php");
    exit();
}

if (isset($_POST['command']) && $_POST['command'] === "Update" && $valid) {
    $query = "UPDATE post SET post_title = :post_title, post_content = :post_content WHERE post_id = :post_id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_title', $title);
    $statement->bindValue(':post_content', $content);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statement->execute();
    header("location:blog.php");
    exit();
}

if (isset($_POST['command']) && $_POST['command'] === "Comment" & $validComment) {
    $query = "INSERT INTO comment (comment_content, post_id, user_id) VALUES (:post_comment, :post_id, :id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_comment', $comment);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statement->bindValue(':id', $_SESSION['id']);
    $statement->execute();
    header("location:show.php?post_id=" . $id);
    exit();
}

$pagename = "Error!";
$sitename = "Dominic's Porfolio Website";
$titletag = $pagename . " - " . $sitename;
require_once("header.php");
?>
<main>
    <div id="wrapper">
        <div id="header">
        </div>
        <h1>An error occured while processing your post.</h1>
        <p>
            Invalid content submitted. </p>
        <a href="blog.php">Return to Blog</a>
    </div>
</main>
<?php require_once("footer.php"); ?>