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
                                <h2 class="card-title text-center"><?= $row['post_title'] ?></h2>
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
                                    <?php if (isset($row['post_image'])) : ?>
                                        <div class="d-flex justify-content-center">
                                            <img src="uploads/<?= $row['post_image'] ?>" class="card-img-top w-25" alt="Image of <?= $row['post_title'] ?>">
                                        </div>
                                    <?php endif ?>
                                    <div class="mb-3">
                                        <label for="post_title" class="form-label">Title</label>
                                        <input type="text" class="form-control" name="post_title" id="post_title" value="<?= $row['post_title'] ?>">
                                    </div>
                                    <div class=" mb-3">
                                        <label for="post_content" class="form-label">Content</label>
                                        <textarea class="form-control" name="post_content" id="post_content" rows="15"><?= $row['post_content'] ?></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <?php if (isset($row['post_image'])) : ?>
                                            <div class="row">
                                                <div class="col-3">
                                                    <p>Current image: <?= $row['post_image'] ?></p>
                                                </div>
                                                <div class="col">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" name="imageCommand" value="RemoveImage" id="imageCommand" onclick="return confirm('Click update to remove image from post.')">
                                                        <label class="form-check-label" for="imageCommand">
                                                            Delete Image
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif ?>
                                        <label for="image" class="form-label">Upload Image</label>
                                        <input class="form-control" type="file" name="image" id="image">
                                    </div>
                                    <div class="text-center">
                                        <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                        <button type="submit" name="command" value="Update" class="btn btn-primary">Update</button>
                                        <button type="submit" name="command" value="Delete" class="btn btn-primary" onclick="return confirm('Are you sure you wish to delete this post?')">Delete Post</button>
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