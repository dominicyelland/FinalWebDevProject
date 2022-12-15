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

$pagename = "Edit Post";
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
                                <h2 class="card-title text-center">Edit Blog Post</h2>
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link border border-secondary" href="blog.php">Return</a>
                                    </li>
                                    <?php if ($isAdmin) : ?>
                                        <li class="nav-item">
                                            <a class="nav-link border border-secondary" href="create.php">New Post</a>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                            <div class="card-body">
                                <form action="process_post.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="post_title" class="form-label">Title</label>
                                        <input type="text" class="form-control" name="post_title" id="post_title" value="<?= $row['post_title'] ?>">
                                    </div>
                                    <div class=" mb-3">
                                        <label for="post_content" class="form-label">Content</label>
                                        <textarea class="form-control" name="post_content" id="post_content" rows="15"><?= $row['post_content'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Image</label>
                                        <input class="form-control" type="file" name="image" id="image" value="<?= $row['post_image'] ?>">
                                    </div>
                                    <div class="text-center">
                                        <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                        <button type="submit" name="command" value="Update" class="btn btn-primary">Update</button>
                                        <button type="submit" name="command" value="Delete" class="btn btn-primary" onclick="return confirm('Are you sure you wish to delete this post?')">Delete</button>
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