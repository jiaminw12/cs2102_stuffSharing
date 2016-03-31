<?php
session_start();
$current_page = 'Home';
?>

<?php ob_start(); ?>
<link href="css/vertical.css" rel="stylesheet">
<div class="inner cover">
    <h1 class="cover-heading">Welcome to ShareIt</h1>
    <p class="lead">Stuff Sharing site</p>
    <p class="lead">
        <div id="imaginary_container"> 
            <div class="input-group stylish-input-group">
                <input id="searchText" type="text" class="form-control" placeholder="Search" >
                <span class="input-group-addon">
                    <button type="submit">
                        <span class="glyphicon glyphicon-search"></span>
                    </button>  
                </span>
            </div>
        </div>
</p>
</div>
<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
