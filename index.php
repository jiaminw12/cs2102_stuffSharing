<?php
include("controller/BidController.php");
$sb = \BidController\removeAllBidsByItemID();

session_start();
$current_page = 'Home';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: search.php?keyword=" . $_POST['searchText']);
}
?>

<?php ob_start(); ?>
<link href="css/vertical.css" rel="stylesheet">
<div class="inner cover">
    <h1 class="cover-heading">Welcome to ShareIt</h1>
    <p class="lead">Stuff Sharing site</p>
    <p></p>
    <form method="post" role="form">
        <div class="form-group">
            <div id="imaginary_container"> 
                <div class="input-group stylish-input-group">
                    <input id="searchText" name="searchText" type="text" class="form-control" placeholder="Search...">
                    <span class="input-group-addon">
                        <button id="submit" name="submit" type="submit">
                            <span class="glyphicon glyphicon-search"></span>
                        </button>    
                    </span>
                </div>
            </div>
        </div>
    </form>
</div>

<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
