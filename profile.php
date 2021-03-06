<?php
session_start();
$current_page = 'Profile';
include_once "controller/UserController.php";
include_once "controller/ItemController.php";
include_once "controller/BidController.php";
include_once "controller/BorrowController.php";

$username = $_SESSION["username"];

$_SESSION['previous_location'] = 'profile';
$userList = UserController\getUser($username);
$owner = $userList->getEmail();

if ($userList->getAdmin() == 1) {
    $itemList = ItemController\getAllItems();
} else {
    $itemList = ItemController\getAvailableItem($owner);
}

// on profile update
if (isset($_POST['update-profile-submit'])) {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $contact_num = $_POST['contact_num'];
    $email = $userList->getEmail();
    $userList = UserController\updateProfile($username, $name, $contact_num, $email);
    $username = $userList->getUsername();
    header("Location: profile.php");
}

// update password
if (isset($_POST['update-password-submit'])) {
    $currentpassword = $_POST['current-password'];
    $newpassword = md5($_POST['new-password']);
    if (UserController\isCorrectPassword($username, $currentpassword)) {  // current password correct
        $email = $userList->getEmail();
        $userList = UserController\updatePassword($newpassword, $email);
        header("Location: profile.php");
    } else {
        $message = "Wrong current password";
        $message_type = "danger";
    }
}

// delete
if (isset($_POST['delete-submit'])) {
    $itemiD = $_POST['delete-itemID'];
    $userList = ItemController\removeItem($itemiD);
    if ($userList->getAdmin() == 1) {
        $itemList = ItemController\getAllItems();
    } else {
        $itemList = ItemController\getAvailableItem($owner);
    }
    header("Location: profile.php");
}

// update bid point
if ($_POST['update-bid']) {
    $curbid = $_POST['curbid'];
    $itemiD = $_POST['updatebid-itemID'];
    $bid_point = $_POST['update-bidpoint'];
    $prevbid = $_POST['prev-bid'];
    $owner = $_POST['updatebid-owner'];
    $bidder = $_POST['updatebid-bidder'];
    if ($curbid < $bid_point) {
        $message = "Not enough bid point";
        $message_type = "danger";
    } else {
        $curbid = $curbid + $prevbid - $bid_point;
        $userList = BidController\updateBidPoint($owner, $bidder, $itemiD, $bid_point);
        $userList = UserController\updateUserBidPoint($username, $curbid);
        if ($userList->getAdmin() == 1) {
            $itemList = ItemController\getAllItems();
        } else {
            $itemList = ItemController\getAvailableItem($owner);
        }
        if ($userList->getAdmin() == 1) {
            $bidderList = BidController\getAllBids();
        } else {
            $bidder = $userList->getEmail();
            $bidderList = BidController\getSelectedBidByUser($bidder);
        }
        header("Location: profile.php");
    }
}

// delete bid
if ($_POST['delete-bid']) {
    $curbid = $_POST['curbid'];
    $prevbid = $_POST['prev-bid'];
    $itemiD = $_POST['updatebid-itemID'];
    $bid_point = $_POST['update-bidpoint'];
    $owner = $_POST['updatebid-owner'];
    $bidder = $userList->getEmail();

    $userList = BidController\removeBidByItemID($itemiD);
    $curbid = $curbid + $prevbid;
    $userList = UserController\updateUserBidPoint($username, $curbid);
    if ($userList->getAdmin() == 1) {
        $itemList = ItemController\getAllItems();
    } else {
        $itemList = ItemController\getAvailableItem($owner);
    }
    header("Location: profile.php");
}

// delete borrow
if ($_POST['delete-borrow-submit']) {
    $itemiD = $_POST['delete-borrow-itemID'];
    $ownerbo = $_POST['delete-borrow-owner'];
    $borrower = $_POST['delete-borrow-borrower'];
    
    $userList = BorrowController\removeBorrow($ownerbo, $borrower, $itemiD);
    if ($userList->getAdmin() == 1) {
        $itemList = ItemController\getAllItems();
    } else {
        $itemList = ItemController\getAvailableItem($owner);
    }
    if ($userList->getAdmin() == 1) {
        $borrow = BorrowController\getAllBorrows();
    } else {
        $borroweremail = $userList->getEmail();
        $borrow = BorrowController\getAllBorrowsByUser($borroweremail);
    }
    header("Location: profile.php");
}

// update borrow
if ($_POST['update-available-submit']) {
    $itemiD = $_POST['delete-borrow-itemID'];
    $ownerbo = $_POST['delete-borrow-owner'];
    $borrower = $_POST['delete-borrow-borrower'];
    
    $xxx = ItemController\updateAvailable($itemiD, 1);
    $userList = BorrowController\updateStatus($ownerbo, $borrower, $itemiD, 2);
    
    if ($userList->getAdmin() == 1) {
        $itemList = ItemController\getAllItems();
    } else {
        $itemList = ItemController\getAvailableItem($owner);
    }
    if ($userList->getAdmin() == 1) {
        $borrow = BorrowController\getAllBorrows();
    } else {
        $borroweremail = $userList->getEmail();
        $borrow = BorrowController\getAllBorrowsByUser($borroweremail);
    }
    
    header("Location: profile.php");
}
?>


<?php ob_start(); ?>
<br/>
<h1>44566721</h1>
<div class="inner cover container">
    <?php
    include_once 'template/message.php';
    if ($message_type != 'danger') {
        echo $message;
        echo $message_type;
    } else {
        
    }
    ?>
    <span class="black">
        <div class="well" align="left">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#home" data-toggle="tab">Profile</a></li>
                <li><a href="#profile" data-toggle="tab">Password</a></li>
                <li><a href="#item" data-toggle="tab">Items</a></li>
            </ul>
            <div id="myTabContent" class="tab-content">
                <div class="tab-pane active in" id="home" align="left">
                    <br/>
                    <form id="tab" method='POST'>
                        <label>Username</label><br/>
                        <input type="hidden" value="<?php echo $userList->getUsername(); ?>" class="input-xlarge" name='username' id='input-username' placeholder='username'>
                        <?php echo $userList->getUsername(); ?><br/>
                        <label>Name</label><br/>
                        <input type="text" value="<?php echo $userList->getName(); ?>" class="input-xlarge" name ='name' id='input-name' placeholder='name'><br/>
                        <label>Contact Number</label><br/>
                        <input type="text" value="<?php echo $userList->getContactNum(); ?>" class="input-xlarge" name='contact_num' id='input-contact-num' placeholder='contact_num'><br/>
                        <label>Email</label><br>
                        <?php echo $userList->getEmail(); ?><br/>
                        <label>Current bid point</label><br/>
                        <?php echo $userList->getBidPoint(); ?><br/>
                        <br/>
                        <div align = "center">
                            <button class="btn btn-primary" name='update-profile-submit' type='submit'>Update</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="profile" align="left">
                    <br/>
                    <form id="tab2" method='POST'>
                        <label>Current Password</label>
                        <input type="password" name='current-password' class="form-control" id="input-current-password" placeholder="Current password">
                        <br/>
                        <label>New Password</label>
                        <input type="password" name='new-password' class="form-control" id="input-new-password" placeholder="New password">
                        <br/>
                        <div>
                            <button class="btn btn-primary" name='update-password-submit' type='submit'>Update Now</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="item" align="left">
                    <br/>
                    <div class="span7">
                        <div class="widget stacked widget-table action-table">
                            <div class="widget-header">
                                <i class="icon-th-list"></i>
                                <h4>Available Items</h4>
                            </div> <!-- /widget-header -->
                            <div class="widget-content">

                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Title</th>
                                            <th>Current Bid Points</th>
                                            <th class="td-actions"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($itemList as $item) { ?>
                                            <tr>
                                                <td><?php echo $item->getItemTitle(); ?></td>
                                                <td><?php
                                                    $item_avid = $item->getItemId();
                                                    $bidList = BidController\getTheHighestBidPoint($item_avid);
                                                    if ($bidList[0][0] > 0) {
                                                        echo $bidList[0][0];
                                                    } else {
                                                        echo $item->getBidPointStatus();
                                                    }
                                                    ?>
                                                </td> 
                                                <td>
                                                    <form id="tab3" method='POST'>
                                                        <a href="edit_item.php?id=<?php echo $item_avid ?>" class="btn btn-primary white">edit</a>
                                                        <input type="hidden" name = "delete-itemID" value = "<?php echo $item->getItemId(); ?>">
                                                        <button class="btn btn-warning" name='delete-submit' type="submit">Delete</button> 
                                                    </form>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>  


                            <div class="widget-header">
                                <i class="icon-th-list"></i>
                                <h4>Borrowed Items</h4>
                            </div> <!-- /widget-header -->
                            <div class="widget-content">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Borrow Start Date</th>
                                                <th>Borrow End Date</th>
                                                <th>Return Location</th>
                                                <?php if ($userList->getAdmin() == 1) { ?>
                                                    <th class="td-actions"></th>
                                                <?php } ?>

                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($userList->getAdmin() == 1) {
                                                $borrow = BorrowController\getAllBorrows();
                                            } else {
                                                $borroweremail = $userList->getEmail();
                                                $borrow = BorrowController\getAllBorrowsByUser($borroweremail);
                                            }
                                            foreach ($borrow as $borrowitem) {
                                                ?>
                                                <tr>
                                                    <td><?php
                                                        $borrow_id = $borrowitem->getItemId();
                                                        $borrowtitle = ItemController\getItem($borrow_id);
                                                        foreach ($borrowtitle as $titleBor) {

                                                            echo $titleBor->getItemTitle();
                                                            ?></td>
                                                        <td><?php echo $titleBor->getBorrowStartDate(); ?></td> 
                                                        <td><?php echo $titleBor->getBorrowEndDate(); ?></td> 
                                                        <td><?php
                                                            echo $titleBor->getReturnLocation();
                                                        }
                                                        ?></td>

                                                    <?php 
                                                    $v1 = $userList->getEmail();
                                                    $v2 = $borrowitem->getOwner();
                                                    if ($userList->getAdmin() == 1 || (strcmp($v1, $v2) === 0)) { ?>
                                                        <td> 
                                                            
                                <form method="POST" class="form" role="form" action="profile.php">
                                                            <input type="hidden" name = "delete-borrow-itemID" value = "<?php echo $borrowitem->getItemId(); ?>">

                                                            <input type="hidden" name = "delete-borrow-owner" value = "<?php echo $borrowitem->getOwner(); ?>">
                                                            <input type="hidden" name = "delete-borrow-borrower" value = "<?php echo $borrowitem->getBorrower(); ?>">
                                                            <?php if ($borrowitem->getStatus() == 1){?>
                                                            <input class="btn btn-primary" name="update-available-submit" type="submit"value="Returned">
                                                    <?php } ?>
                                                            <input class="btn btn-warning" name="delete-borrow-submit" type="submit"value="Delete">          
                                </form>
                                                        </td>
                                                    <?php } else { ?>

                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </tbody>

                                    </table>

                            </div> 


                            <div class="widget-header">
                                <i class="icon-th-list"></i>
                                <h4>Current Bidding Items</h4>
                            </div> <!-- /widget-header -->
                            <div class="widget-content">
                                <form method="POST" class="form" role="form" action="profile.php">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Title</th>
                                                <th>Bid End Date</th>
                                                <th>Current Bid Point</th>
                                                <th>Bid Point</th>
                                                <th class="td-actions"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($userList->getAdmin() == 1) {
                                                $bidderList = BidController\getAllBids();
                                            } else {
                                                $bidder = $userList->getEmail();
                                                $bidderList = BidController\getSelectedBidByUser($bidder);
                                            }
                                            foreach ($bidderList as $bidItem) {
                                                ?>
                                                <tr>
                                                    <td><?php
                                                        $item_id = $bidItem->getItemId();
                                                        $item_title = ItemController\getItem($item_id);
                                                        foreach ($item_title as $titleItem) {
                                                            echo $titleItem->getItemTitle();
                                                            ?></td>

                                                        <td><?php
                                                            echo $titleItem->getBidEndDate();
                                                        }
                                                        ?></td> 
                                                    <td><?php
                                                        $item_id = $bidItem->getItemId();
                                                        $bidList = BidController\getTheHighestBidPoint($item_id);
                                                        if ($bidList[0][0] > 0) {
                                                            echo $bidList[0][0];
                                                        } else {
                                                            echo $item->getBidPointStatus();
                                                        }
                                                        ?></td>

                                                    <td>
                                                        <input type="hidden" name = "curbid" value = "<?php echo $userList->getBidPoint(); ?>">
                                                        <input type="hidden" name = "prev-bid" value = "<?php echo $bidItem->getBidPoint(); ?>">
                                                        <input type="text" value="<?php echo $bidItem->getBidPoint(); ?>"class="input-xlarge" name="update-bidpoint" id='input-update-bids' placeholder='update-bids' >
                                                    </td>
                                                    <td width="50%">
                                                        <input type="hidden" name = "updatebid-itemID" value = "<?php echo $bidItem->getItemId(); ?>">
                                                        <input type="hidden" name = "updatebid-owner" value = "<?php echo $bidItem->getOwner(); ?>">
                                                        <input type="hidden" name = "updatebid-bidder" value = "<?php echo $bidItem->getBidder(); ?>">

                                                        <input class="btn btn-primary" name="update-bid" type="submit"value="Update"> 
                                                        <input class="btn btn-warning" name="delete-bid" type="submit"value="Delete"> 
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </span>
</div>

<?php
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>