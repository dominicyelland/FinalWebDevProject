<?php
require('connect.php');
require('authenticate.php');

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
$valid = !empty($title) && !empty($content) && strlen($title) > 0 && strlen($content) > 0 ? true : false;
$error = false;
$image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);

if ($image_upload_detected) {
    $image_filename       = $_FILES['image']['name'];
    $temporary_image_path = $_FILES['image']['tmp_name'];
    $new_image_path       = file_upload_path($image_filename);

    if (file_is_an_image($temporary_image_path, $new_image_path) && $valid) {
        move_uploaded_file($temporary_image_path, $new_image_path);
    } else {
        $error = true;
    }
}

if ($valid & !$error) {
    $query = "INSERT INTO post (post_title, post_content, post_image, user_id) VALUES (:post_title, :post_content, :post_image, :user_id)";
    $statement = $db->prepare($query);
    $statement->bindValue(':post_title', $title);
    $statement->bindValue(':post_content', $content);
    $statement->bindValue(':post_image', $image_filename);
    $statement->bindValue(':user_id', $_SESSION['id']);
    $statement->execute();
    header("location:blog.php");
    exit();
} else if (!$valid && !$error && isset($_POST['post_title']) && isset($_POST['post_content'])) {
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
                                <form action="create.php" method="post" enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <label for="post_title" class="form-label">Title</label>
                                        <input type="text" class="form-control" name="post_title" id="post_title">
                                    </div>
                                    <div class="mb-3">
                                        <label for="post_content" class="form-label">Content</label>
                                        <textarea class="form-control" name="post_content" id="post_content" rows="15"></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label for="image" class="form-label">Upload Image</label>
                                        <input class="form-control" type="file" name="image" id="image">
                                        <?php if ($error) : ?>
                                            <p class="pt-2">Invalid file type uploaded or missing content!</p>
                                        <?php endif ?>
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