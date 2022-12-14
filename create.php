<?php
require('connect.php');
require('authenticate.php');

$title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$valid = !empty($title) && !empty($content) && strlen($title) > 0 && strlen($content) > 0 ? true : false;

if ($valid) {
    $query = "INSERT INTO post (post_title, post_content, user_id) VALUES (:post_title, :post_content, :user_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_title', $title);
    $statement->bindValue(':post_content', $content);
    $statement->bindValue(':user_id', $_SESSION['id']);
    $statement->execute();
    header("location:blog.php");
    exit();
} else if (!$valid && isset($_POST['post_title']) && isset($_POST['post_content'])) {
    header("location:process_post.php");
    exit();
}

$pagename = "Create Post";
$sitename = "Dominic's Porfolio Website";
$titletag = $pagename . " - " . $sitename;
require_once("header.php");
?>
<main>
    <div class="bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="container p-5">
                        <div class="card text-bg-light mb-3">
                            <div class="card-header">
                                <h2 class="card-title text-center">Create a New Post!</h2>
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link border border-secondary" href="blog.php">Return</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <form action="create.php" method="post">
                                    <div class="mb-3">
                                        <label for="post_title" class="form-label">Title</label>
                                        <input type="text" class="form-control" name="post_title" id="post_title">
                                    </div>
                                    <div class="mb-3">
                                        <label for="post_content" class="form-label">Content</label>
                                        <textarea class="form-control" name="post_content" id="post_content" rows="15"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="formFile" class="form-label">Upload Image</label>
                                        <input class="form-control" type="file" id="formFile">
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" name="command" value="Create" class="btn btn-primary">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>