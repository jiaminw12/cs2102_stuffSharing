<?php
session_start();
$current_page = 'ItemsDetail';
include_once "controller/UserController.php";
include_once "controller/ItemController.php";
include_once 'controller/BidController.php';
include_once 'controller/BorrowController.php';

$page = $current_page;
$username = $_SESSION["username"];

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$item_id = htmlspecialchars($_GET["id"]);

if (!empty($_POST['submit_bid'])) {
    // get the amout
    // do math
    // then update to usercontroller
    // insert into bids
}

if ($_POST['submit_borrow']) {
    $owner = $_POST['owneremail'];
    $borrower = UserController\getEmail($username);
    // 1 -> borrowed; 2 -> returned; 3 -> overdue
    $status = 1;

    $borrows = BorrowController\createNewBorrow($owner, $borrower, $item_id, $status);
    $ua = ItemController\updateAvailable($item_id, 0);
    $message = "New borrow transaction added";
    $message_type = "success";
    
}
?>

<?php ob_start(); ?>

<div class="container">
    <h1 class="black">tr</h1>
    <?php
    include_once 'template/message.php';
    ?>

    <?php
    $itemList = ItemController\getItem($item_id);
    foreach ($itemList as $item) {
        $title = $item->getItemTitle();
        $owner_email = $item->getOwner();
        ?>
        <div class="row">
            <div class="col-lg-12">
                <h2 class="page-header"><?php echo $title ?> by <?php echo UserController\getUsername($owner_email) ?></h2>
            </div>
        </div>

        <div class="row">

            <div class="col-md-4">
                <img class="img-responsive" src="uploadFiles/<?php echo $item->getItemImage() ?>" alt="" style="height: 150px;">
            </div>

            <div class="col-md-8">
                <h4><?php echo $item->getDescription() ?></h4>
                <p><?php echo $item->getPickupLocation() ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $item->getReturnLocation(); ?></p>
                <p>Loan date: <?php echo $item->getBorrowStartDate() ?> <span class="glyphicon glyphicon-arrow-right"></span> <?php echo $item->getBorrowEndDate(); ?></p>
                <p></p>
                <?php
                if (UserController\isSignedIn() && $item->getAvailable() != 0 ) {
                    if (strcmp(UserController\getUsername($item->getOwner()), $username) !== 0) {
                        if ($item->getBidPointStatus() > 0) {
                            ?>
                            <p>
                                <?php
                                if (BidController\getSelectedBidBoolean($item_id)) {
                                    $bidList = BidController\getTheHighestBidPoint($item_id);
                                    foreach ($bidList as $bidDetail) {
                                        $bidder = $bidDetail->getBidder();
                                        $highestBidPoint = $bidDetail->getBidPoint();
                                        ?>
                                    <p>Current Highest Bid Point : <?php echo $highestBidPoint; ?></p>
                                    <?php
                                }
                                $result = BidController\getSelectedBidByUserAndItemID(UserController\getEmail($username), $item_id);
                                $value = $result;
                            } else {
                                $value = $item->getBidPointStatus();
                                echo $value;
                                ?>
                                <p>Current Highest Bid Point : <?php echo $value; ?></p>
                            <?php } ?>
                            <form method="POST" class="form" role="form">
                                <input class="hidden" value="<?php echo $owner_email ?>" name="owneremail" id="owneremail"/>
                                <input class="form-control" id="bid_point" name="bid_point" placeholder="Bid Point" required type="integer" value="<?php echo $value + 1; ?>">
                                <button class="btn btn-success btn-lg btn-block" id="submit" name="submit_bid" type="button">Bid</button>
                            </form>
                        <?php } else { ?>
                            <form method="POST" class="form" role="form">
                                <input class="hidden" value="<?php echo $owner_email ?>" name="owneremail" id="owneremail"/>
                                <input class="btn btn-success btn-lg btn-block" id="submit_borrow" name="submit_borrow" type="submit" value="Borrow">
                            </form>
                        <?php } ?>
                    <?php } ?>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
</div>

<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
