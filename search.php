<?php
session_start();
$current_page = 'Search';
include_once "controller/UserController.php";
include_once "controller/ItemController.php";
include_once "controller/CategoryController.php";

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$searchKeyword = htmlspecialchars($_GET["keyword"]);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header("Location: search.php?keyword=" . $_POST['searchText']);
}
?>

<?php ob_start(); ?>
<script src="js/foundation/foundation.js"></script>
<script src="js/foundation/foundation.slider.js"></script>
<script>
    $(document).foundation('slider', 'reflow');
</script>

<div class="row">
    <p>testing 2</p>
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

    <div class="large-12 text-center columns">
        <h3><?php echo $searchKeyword; ?></h3>
        <br>

        <?php
        $itemList = ItemController\searchItem($searchKeyword);
        if ($itemList !== NULL) {
            foreach ($itemList as $item) {
                ?>
                <div class="row">
                    <hr/>
                    <div class="col-md-6">
                        <h4 class = "text-left"> <?php echo $item->getItemTitle(); ?>
                            <p></p>
                            <?php echo $item->getDescription(); ?>
                            <a href="itemDetail.php?id=<?php echo $item->getItemId(); ?>" class="btn btn-primary white">View Item <span class="glyphicon glyphicon-chevron-right"></span></a>
                        </h4>
                    </div>
                </div>
            <?php }
        }
        ?>
    </div>
</div>


<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
