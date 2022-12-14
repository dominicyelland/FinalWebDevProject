<?php
require('connect.php');
require('authenticate.php');

$query = "SELECT * FROM user";
$statement = $db->prepare($query);
$statement->execute();

$pagename = "Admin Control";
$sitename = "Dominic's Porfolio Website";
$titletag = $pagename . " - " . $sitename;
require_once("header.php");
?>
<main>
    <div class="bg-secondary">
        <div class="container p-5">
            <div class="row">
                <div class="col">
                    <div class="card border-dark bg-dark text-light text-center p-5">
                        <div class="card-header text-warning">
                            Admin Control Panel
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Registered Users</h5>
                            <p class="card-text">Select any user and administer desired command via button after selection.</p>
                            <div class="btn-group mt-3 mb-3" role="group" aria-label="Basic example">
                                <button type="buttonAdd" class="btn btn-primary">Add</button>
                                <button type="buttonUpdate" class="btn btn-primary">Update</button>
                                <button type="buttonDelete" class="btn btn-primary">Delete</button>
                            </div>
                            <select class="form-select" aria-label="Default select example">
                                <option selected>Open for users:</option>
                                <?php while ($users = $statement->fetch()) : ?>
                                    <option value="<?= $users['user_id'] ?>"><?= $users['username'] ?></option>
                                <?php endwhile ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<?php require_once("footer.php"); ?>