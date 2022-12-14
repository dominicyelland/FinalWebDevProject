<?php
require('connect.php');
require('authenticate.php');

if (filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT)) {
    $id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM post WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statement->execute();

    if ($statement->rowCount() < 1) {
        header("location:blog.php");
        exit();
    } else {
        $row = $statement->fetch();
    }
} else {
    header("location:blog.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>My Blog - Edit Post</title>
    <link rel="stylesheet" href="styles.css" type="text/css">
</head>

<body>
    <div id="wrapper">
        <div id="header">
            <h1><a href="blog.php">My Blog - Edit Post</a></h1>
        </div>
        <ul id="menu">
            <li><a href="index.html">Home</a></li>
            <li><a href="create.php">New Post</a></li>
        </ul>
        <div id="all_blogs">
            <form action="process_post.php" method="post">
                <fieldset>
                    <legend>Edit Blog Post</legend>
                    <p>
                        <label for="post_title">Title</label>
                        <input name="post_title" id="post_title" value="<?= $row['post_title'] ?>">
                    </p>
                    <p>
                        <label for="post_content">Content</label>
                        <textarea name="post_content" id="post_content"><?= $row['post_content'] ?></textarea>
                    </p>
                    <p>
                        <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                        <input type="submit" name="command" value="Update">
                        <input type="submit" name="command" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')">
                    </p>
                </fieldset>
            </form>
        </div>
        <div id="footer">
            Copywrong 2022 - No Rights Reserved
        </div>
    </div>
</body>

</html>