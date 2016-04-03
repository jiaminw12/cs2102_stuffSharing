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

$_SESSION['previous_location'] = 'itemDetail';

$item_id = htmlspecialchars($_GET["id"]);

if ($_POST['submit_bid']) {
    $owner = $_POST['owneremail'];
    $bidder = UserController\getEmail($username);
    $latestPoint = $_POST['latest_user_bid_point'];
    $curHighPoint = $_POST['curHighestPoint'];
    if ($latestPoint < $curHighPoint || $latestPoint == $curHighPoint) {
        $message = "Current bid point must higher than current highest bid point.";
        $message_type = "danger";
    } else {
        $userPoint = UserController\getUserBidPoint($username);
        $pPoint = $_POST['prevPoint'];
        if ($pPoint == 0) {
            $bidPointToDeduct = $latestPoint;
        } else {
            $bidPointToDeduct = $latestPoint - $pPoint;
        }
        if ($bidPointToDeduct > $userPoint[0]) {
            $message = "No enough points to bid.";
            $message_type = "danger";
        } else {
            $bid_point = $latestPoint;
            // check whether is exist
            $status = BidController\getSelectedBidByUserAndItemID($bidder, $item_id);
            if ($status == NULL) {
                $bids = BidController\createNewBid($owner, $bidder, $item_id, $bid_point);
            } else {
                $bids = BidController\updateBidPoint($owner, $bidder, $item_id, $bid_point);
            }
            $finalPoint = $userPoint[0] - $bidPointToDeduct;
            UserController\updateUserBidPoint($username, $finalPoint);
            header("Location: itemDetail.php?id=" . $item_id);
        }
    }
}

if ($_POST['submit_borrow']) {
    $owner = $_POST['owneremail'];
    $borrower = UserController\getEmail($username);
    // 1 -> borrowed; 2 -> returned; 3 -> overdue
    $status = 1;

    $borrows = BorrowController\createNewBorrow($owner, $borrower, $item_id, $status);
    $ua = ItemController\updateAvailable($item_id, 0);
}
?>

<?php ob_start(); ?>

<div class="container">

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
                if (UserController\isSignedIn() && $item->getAvailable() != 0) {
                    if (strcmp(UserController\getUsername($item->getOwner()), $username) !== 0) {
                        if ($item->getBidPointStatus() > 0) {
                            ?>
                            <p>
                                <?php
                                if (BidController\getSelectedBidBoolean($item_id)) {
                                    $bidList = BidController\getTheHighestBidPoint($item_id);
                                    foreach ($bidList as $bidDetail) {
                                        $currentHighestBidPoint = $bidDetail[0];
                                        ?>
                                    <p><b>Current Highest Bid Point : <?php echo $currentHighestBidPoint; ?></b></p>
                                    <?php
                                }
                                $result = BidController\getSelectedBidByUserAndItemID(UserController\getEmail($username), $item_id);
                                if ($result != NULL) {
                                    $previousUserPoint = $result[0];
                                } else {
                                    $previousUserPoint = 0;
                                }
                            } else {
                                $currentHighestBidPoint = $item->getBidPointStatus();
                                $previousUserPoint = 0;
                                ?>
                                <p><b>Current Highest Bid Point : <?php echo $currentHighestBidPoint; ?></b></p>
                            <?php } ?>
                            <form method="POST" class="form" role="form">
                                <input class="hidden" value="<?php echo $previousUserPoint ?>" name="prevPoint" id="prevPoint"/>
                                <input class="hidden" value="<?php echo $currentHighestBidPoint ?>" name="curHighestPoint" id="curHighestPoint"/>
                                <input class="hidden" value="<?php echo $owner_email ?>" name="owneremail" id="owneremail"/>
                                <input class="form-control" id="latest_user_bid_point" name="latest_user_bid_point" required type="integer" value="<?php echo $previousUserPoint; ?>">
                                <input class="btn btn-success btn-lg btn-block" id="submit_bid" name="submit_bid" type="submit" value="Bid">
                            </form>
                        <?php } else { ?>
                            <form method="POST" class="form" role="form">
                                <input class="hidden" value="<?php echo $owner_email ?>" name="owneremail" id="owneremail"/>
                                <input class="btn btn-success btn-lg btn-block" id="submit_borrow" name="submit_borrow" type="submit" value="Borrow">
                            </form>
                        <?php } ?>
                    <?php } else { ?>
                                <a href="edit_item.php?id=<?php echo $item_id; ?>" class="btn btn-primary white">Edit Item <span class="glyphicon glyphicon-chevron-right"></span></a>
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
