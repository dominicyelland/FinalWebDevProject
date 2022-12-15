<?php
require('connect.php');

function file_upload_path($original_filename, $upload_subfolder_name = 'uploads')
{
    $current_folder = dirname(__FILE__);
    $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
    return join(DIRECTORY_SEPARATOR, $path_segments);
}

function file_is_an_image($temporary_path, $new_path)
{
    $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
    $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];

    $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);

    if (is_array(getimagesize($temporary_path))) {
        $actual_mime_type = getimagesize($temporary_path)['mime'];
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

        return $file_extension_is_valid && $mime_type_is_valid;
    }
}

$title = filter_input(INPUT_POST, 'post_title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$content = filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$valid = !empty($title) && !empty($content) && strlen($title) > 0 && strlen($content) > 0 ? true : false;
$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
$image = null;

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
    if ($image_upload_detected) {
        $image_filename       = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];
        $new_image_path       = file_upload_path($image_filename);

        if (file_is_an_image($temporary_image_path, $new_image_path) && $valid) {
            move_uploaded_file($temporary_image_path, $new_image_path);
            $image = $_FILES['image']['name'];
        } else {
            header("location:process_post.php");
            exit();
        }
    }

    $query = "UPDATE post SET post_title = :post_title, post_content = :post_content, post_image = :post_image WHERE post_id = :post_id LIMIT 1";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_title', $title);
    $statement->bindValue(':post_content', $content);
    $statement->bindValue(':post_image', $image);
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
    <div class="bg-secondary">
        <div class="container">
            <div class="row">
                <div class="col">
                    <div class="container p-5">
                        <div class="card text-bg-light mb-3 text-center">
                            <div class="card-header">
                                <h2 class="card-title">An error occured while processing your post.</h2>
                                <ul class="nav nav-tabs card-header-tabs">
                                    <li class="nav-item">
                                        <a class="nav-link border border-secondary" href="blog.php">Return</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-body">
                                <p class="card-text text-start">Invalid content submitted</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>