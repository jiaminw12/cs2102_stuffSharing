<?php
session_start();
$current_page = 'ItemsDetail';
include_once "controller/UserController.php";
include_once "controller/ItemController.php";

$page = $current_page;

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$parts = parse_url($url);
parse_str($parts['query'], $query);

if (!empty($_POST['submit_bid'])) {
    // 
  
}

if (!empty($_POST['submit_borrow'])) {
  
}

?>

<?php ob_start(); ?>

<br/>
<!-- Page Content -->
<div class="container">
    <?php
    $itemList = ItemController\getItem(htmlspecialchars($_GET["id"]));
    foreach ($itemList as $item) {
        ?>
        <div class="row">
            <div class="col-lg-12">
               
                <h1 class="page-header"><?php echo $item->getItemTitle(); ?> by <?php echo UserController\getUsername($item->getOwner()) ?></h1>
            </div>
        </div>
        <!-- /.row -->

        <!-- Portfolio Item Row -->
        <div class="row">

            <div class="col-md-4">
                <img class="img-responsive" src="uploadFiles/<?php echo $item->getItemImage(); ?>" alt="" style="height: 150px;">
            </div>

            <div class="col-md-8">
                <h3>Project Description</h3>
                <p><?php echo $item->getDescription(); ?></p>
                <p><?php echo $item->getPickupLocation(); ?></p>
                <p><?php echo $item->getReturnLocation(); ?></p>
                <p><?php echo $item->getBorrowStartDate(); ?> - <?php echo $item->getBorrowEndDate(); ?></p>
                
                <?php echo $_SESSION["username"]; ?>
                
                <?php
    if (UserController\isSignedIn()) {
        ?>
              <span class="black"> hhhhhhhhhhhhhhhhhh </span>
        <form method="POST" class="form" role="form" enctype="multipart/form-data">
            <input class="form-control" id="bid_point" name="bid_point" placeholder="Bid Point" required type="integer" value="">
            <button class="btn btn-success btn-lg btn-block" id="submit" name="submit_bid" type="button">Bid</button>
        </form>

    <?php } else { ?>
                <span class="black"> kkkkkkkkkkkkkk </span>
        <form method="POST" class="form" role="form" enctype="multipart/form-data">
            <button class="btn btn-success btn-lg btn-block" id="submit" name="submit_borrow" type="button">Borrow</button>
        </form>
    <?php } ?>
                
            </div>
        </div>
    </div>
    <!-- /.row -->

<?php } ?>

        
<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
