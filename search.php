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

<div class="row">
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
    <p></p>
    <div class="large-12 text-center columns">
        <br>
        <?php
        $tempSearchKeyword = strtolower($searchKeyword);
        if (preg_match('/category/', $tempSearchKeyword)) {
            $result = explode(':', $searchKeyword);
            $itemList = ItemController\searchItemCategory($result[1]); 
            $tempResult = explode(':', $searchKeyword);?>
            <br>
            <h3 class="white"><b><?php echo "Category : " . $tempResult[1] ?></b></h3>
        <?php } else {
            $itemList = ItemController\searchItem($searchKeyword);?>
            <br>
            <h3 class="white"><b><?php echo $searchKeyword; ?></b></h3>
        <?php }
        $totalNum = count($itemList);
        $counter = 0;
        if ($itemList == NULL) {
            ?>
            <h1 class = "white"><b>Sorry, no items found !</b></h1>
            <?php
        } else {
            foreach ($itemList as $item) {
                $id = $item[0];
                $title = $item[1];
                $desc = $item[2];
                $image = $item[3];
                if ($counter % 3 == 0) {
                    ?>
                    <br>
                    <div class="row">
                    <?php } ?>
                    <div class="col-md-4 pin">
                        <h4><b><?php echo $title ?></b></h4>
                        <img src="uploadFiles/<?php echo $image ?>" class="imageCenter" alt="" style="height: 100px;">
                        <p></p>
                        <a href="itemDetail.php?id=<?php echo $id ?>" class="btn btn-primary white">View Item <span class="glyphicon glyphicon-chevron-right"></span></a>
                    </div>
                    <?php
                    $counter++;
                    if ($counter % 3 == 0 || $counter == $totalNum) {
                        ?>
                    </div>
                    <?php
                }
            }
        }
        ?>
    </div>
</div>

<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
