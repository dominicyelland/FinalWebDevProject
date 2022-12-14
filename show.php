<?php
require('connect.php');

if (filter_input(INPUT_GET, 'post_id', FILTER_VALIDATE_INT)) {
    $id = filter_input(INPUT_GET, 'post_id', FILTER_SANITIZE_NUMBER_INT);
    $query = "SELECT * FROM post WHERE post_id = :post_id";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statement->execute();

    $queryTwo = "SELECT * FROM comment JOIN user ON user.user_id = comment.user_id WHERE post_id = :post_id ORDER BY comment_date DESC";
    $statementTwo = $db->prepare($queryTwo);
    $statementTwo->bindValue(':post_id', $id, PDO::PARAM_INT);
    $statementTwo->execute();

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

if (isset($_GET['delete']) && $_GET['delete'] == 'true' && isset($_GET['code'])) {
    if (filter_input(INPUT_GET, 'code', FILTER_VALIDATE_INT)) {
        $code = filter_input(INPUT_GET, 'code', FILTER_SANITIZE_NUMBER_INT);

        $query = "DELETE FROM comment WHERE comment_id = :code LIMIT 1";
        $statement = $db->prepare($query);
        $statement->bindValue(':code', $code, PDO::PARAM_INT);
        $statement->execute();
        header("location:show.php?post_id=" . $row['post_id']);
        exit();
    }
}

$comment = isset($_GET['comment']) && $_GET['comment'] == 'true';
$pagename = $row['post_title'];
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
                        <div class="card text-bg-light mb-3 text-center">
                            <div class="card-header">
                                <h2 class="card-title"><?= $row['post_title'] ?></h2>
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link border border-secondary" href="blog.php">Return</a>
                                    </li>
                                    <?php if ($isAdmin) : ?>
                                        <li class="nav-item">
                                            <a class="nav-link border border-secondary" href="create.php">New Post</a>
                                        </li>
                                    <?php endif ?>
                                    <?php if ($isAdmin || $isUser) : ?>
                                        <li class="nav-item">
                                            <a class="nav-link border border-secondary" href="show.php?post_id=<?= $row['post_id'] ?>&comment=true">Comment</a>
                                        </li>
                                    <?php endif ?>
                                </ul>
                            </div>
                            <div class="card-body">
                                <img src="..." class="card-img-top" alt="Image of <?= $row['post_title'] ?>">
                                <p class="card-text text-start"><?= $row['post_content'] ?></p>
                                <div class="card-footer text-muted">
                                    <p><small><?= date('F j, o, g:i a', strtotime($row['post_date'])) ?></small></p>
                                </div>
                                <?php if ($isAdmin) : ?>
                                    <a href="edit.php?post_id=<?= $row['post_id'] ?>" class="btn btn-primary">Edit!</a>
                                <?php endif ?>
                            </div>
                        </div>
                        <?php if ($comment && ($isUser || $isAdmin)) : ?>
                            <div class="col d-flex justify-content-center">
                                <div class="card text-bg-light text-center w-50">
                                    <div class="card-body">
                                        <form action="process_post.php" method="post">
                                            <label for="post_comment" class="form-label">Comment:</label>
                                            <textarea class="form-control" name="post_comment" id="post_comment" rows="5"></textarea>
                                            <div class="pt-3">
                                                <input type="hidden" name="post_id" value="<?= $row['post_id'] ?>">
                                                <button type="submit" name="command" value="Comment" class="btn btn-primary btn-sm">Post</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif ?>
                        <div class="card m-5">
                            <div class="card-header">
                                Comments:
                            </div>
                            <div class="card-body">
                                <?php while ($comment = $statementTwo->fetch()) : ?>
                                    <blockquote class="blockquote mb-0">
                                        <p><?= $comment['comment_content'] ?></p>
                                        <footer class="blockquote-footer"><cite title="Source Title"><?= $comment['username'] ?></cite> circa <?= $comment['comment_date'] ?>
                                            <?php if ($isAdmin) : ?>
                                                <a href="show.php?post_id=<?= $row['post_id'] ?>&delete=true&code=<?= $comment['comment_id'] ?>" class="btn btn-primary">Delete</a>
                                            <?php endif ?>
                                        </footer>
                                    </blockquote>
                                <?php endwhile ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>