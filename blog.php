<?php
require('connect.php');
$query = "SELECT * FROM post ORDER BY post_date DESC LIMIT 10";
$statement = $db->prepare($query);
$statement->execute();

$querySecond = "SELECT * FROM post ORDER BY post_date DESC";
$statementSecond = $db->prepare($querySecond);
$statementSecond->execute();

$sort = filter_input(INPUT_GET, 'sortBy', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$isSorted = false;

// STILL DOESNT WORK
if (isset($_GET['sortBy']) && ($_GET['sortBy'] == 'post_title' || $_GET['sortBy'] == 'post_date' || $_GET['sortBy'] == 'post_content')) {
  $isSorted = true;

  $queryThird = "SELECT * FROM post ORDER BY :sortBy";
  $statementThird = $db->prepare($queryThird);
  $statementThird->bindValue(':sortBy', $_GET['sortBy']);
  $statementThird->execute();
}


$pagename = "Dominic's Blog";
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
            <div class="card border-dark bg-dark text-light p-5">
              <h1><?= $pagename ?></h1>
              <?php if ($isAdmin) : ?>
                <p>Create new post <a href="create.php" class="text-decoration-none">here!</a></p>
              <?php endif ?>
            </div>

            <?php if (empty($statement->rowCount())) : ?>
              <div class="card text-bg-light mb-3 text-center">
                <div class="card-header">
                  <h2><?= "Apologies, there are no blog posts at the moment." ?></h2>
                </div>
              </div>
            <?php else : ?>
              <?php while ($row = $statement->fetch()) : ?>
                <div class="card text-bg-light mb-3 text-center">
                  <div class="card-header">
                    <h2><a href="show.php?post_id=<?= $row['post_id'] ?>" class="text-decoration-none"><?= $row['post_title'] ?></a></h2>
                  </div>
                  <div class="card-body">
                    <?php if (strlen($row['post_content']) <= 1500) : ?>
                      <p class="card-text text-start"><?= $row['post_content'] ?></p>
                    <?php else : ?>
                      <?= substr((string)$row['post_content'], 0, 1500) ?>
                      ... <a href="show.php?post_id=<?= $row['post_id'] ?>" class="text-decoration-none">Read more</a>
                    <?php endif ?>
                    <div class="card-footer text-muted">
                      <p><small><?= date('F j, o, g:i a', strtotime($row['post_date'])) ?></small></p>
                      <?php if ($isAdmin) : ?>
                        <a href="edit.php?post_id=<?= $row['post_id'] ?>" class="btn btn-primary">Edit!</a>
                      <?php endif ?>
                    </div>
                  </div>
                </div>
              <?php endwhile ?>
            <?php endif ?>
          </div>
        </div>

        <div class="col col-md-auto text-light pt-5">
          <div class="list-group sticky-top">
            <div class="btn-group dropend">
              <button type="button" class="btn btn-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                Sort List By
              </button>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="blog.php?sortBy=post_title">Title</a></li>
                <li><a class="dropdown-item" href="blog.php?sortBy=post_date">Date</a></li>
                <li><a class="dropdown-item" href="blog.php?sortBy=post_content">Content</a></li>
              </ul>
            </div>
            <!-- DOESNT WORK -->
            <?php if (!$isSorted) : ?>
              <?php while ($content = $statementSecond->fetch()) : ?>
                <a href="show.php?post_id=<?= $content['post_id'] ?>" class="list-group-item"><?= $content['post_title'] ?></a>
              <?php endwhile ?>
            <?php else : ?>
              <?php while ($content = $statementThird->fetch()) : ?>
                <a href="show.php?post_id=<?= $content['post_id'] ?>" class="list-group-item"><?= $content['post_title'] ?></a>
              <?php endwhile ?>
            <?php endif ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</main>
<?php require_once("footer.php"); ?>