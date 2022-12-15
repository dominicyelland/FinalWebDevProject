<?php
require('connect.php');

$pagename = "Contact Me!";
$sitename = "Dominic's Porfolio Website";
$titletag = $pagename . " - " . $sitename;
require_once("header.php");
?>
<main>
    <form class="row g-3">
        <div class="col-md-6">
            <label for="inputEmail4" class="form-label">Email</label>
            <input type="email" class="form-control" id="inputEmail4">
        </div>
        <div class="col-md-6">
            <label for="inputPassword4" class="form-label">First Name</label>
            <input type="text" class="form-control" id="inputPassword4">
        </div>
        <div class="col-12">
            <label for="inputAddress" class="form-label">Last Name</label>
            <input type="text" class="form-control" id="inputAddress">
        </div>
        <div class="col-12">
            <label for="inputAddress2" class="form-label">Phone Number</label>
            <input type="tel" class="form-control" id="inputAddress2" placeholder="204-123-4567">
        </div>
        <div class="col-md-6">
            <label for="inputCity" class="form-label">Message</label>
            <textarea type="text" class="form-control" id="inputCity" rows="5"></textarea>
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</main>
<?php require_once("footer.php"); ?>