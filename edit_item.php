<?php
session_start();
$current_page = 'Edit_Items';
include_once "controller/UserController.php";
include_once "controller/ItemController.php";
include_once "controller/CategoryController.php";

$page = $current_page;
$username = $_SESSION["username"];
$previousPage = $_SESSION['previous_location'];

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

$item_id = htmlspecialchars($_GET["id"]);

if ($_POST['submit']) {
    $errors = array();
    $item_id = $_POST['itemid'];
    $item_title = $_POST["item_title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $bid_point_status = $_POST["bid_point_status"];

    if ($bid_point_status == "yes") {
        $bid_point_status = $_POST["min_bid_point"];
        $bid_end_date = $_POST["bid_end_date"];
        $time = $_POST["hours"] . ":" . $_POST["minutes"] . ":" . $_POST["seconds"];
        $bid_end_date = $bid_end_date . " " . $time;
    } else if ($bid_point_status == "no") {
        $bid_point_status = 0;
        $bid_end_date = "NULL";
    }
    $available = $_POST['iavailable'];
    $pickup_location = $_POST["pickup_location"];
    $return_location = $_POST["return_location"];
    $borrow_start_date = $_POST["borrow_start_date"];
    $borrow_end_date = $_POST["borrow_end_date"];

    if ($bid_end_date != "NULL") {
        if ($borrow_start_date < $bid_end_date || $borrow_end_date < $bid_end_date) {
            $errors[] = "Borrow start date must be after bid end date.";
        }
    }

    if ($borrow_end_date < $borrow_start_date) {
        $errors[] = "Borrow end date must be after borrow start date.";
    }

    if (empty($errors) == true) {
        $items = ItemController\updateItemDetails($item_id, $item_title, $description, $bid_point_status, $available, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date);
        if ($previousPage === 'itemDetail'){
            header("Location: itemDetail.php?id=". $item_id);
        } else if ($previousPage === 'profile'){
            header("Location: profile.php");
        }
    } else {
        foreach ($errors as $err){
            $message = $err . " " . $message;
        }
        $message_type = "danger";
    }
}
?>

<?php ob_start(); ?>

<script type="text/javascript">
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth() + 1; //January is 0!

    var yyyy = today.getFullYear();
    if (dd < 10) {
        dd = '0' + dd
    }
    if (mm < 10) {
        mm = '0' + mm
    }
    var today = yyyy + '-' + mm + '-' + dd;

    $(document).ready(function() {
        $('#bid_end_date').datepicker({});
        $('#borrow_start_date').datepicker({});
        $('#borrow_end_date').datepicker({});

        $("#bid_point_status").change(function() {
            $(this).find("option:selected").each(function() {
                if ($(this).attr("value") == "yes") {
                    $("#bidDate").removeClass('hidden');
                    $("#bid_timming").removeClass('hidden');
                    $("#min_bid_point").removeClass('hidden');
                } else {
                    $("#bidDate").addClass('hidden');
                    $("#bid_timming").addClass('hidden');
                    $("#min_bid_point").addClass('hidden');
                    document.getElementById("bid_end_date").value = today;
                }
            });
        }).change();
    });

</script>

<br/>
<div class="inner cover container">
    <h1 class="black">eeqq</h1>
    <?php
    include_once 'template/message.php';
    $itemList = ItemController\getItem($item_id);
    foreach ($itemList as $item) {
        $iId = $item->getItemId();
        $iOwner = $item->getOwner();
        $iTitle = $item->getItemTitle();
        $iDesc = $item->getDescription();
        $iCategory = $item->getCategory();
        $iBidPoint = $item->getBidPointStatus();
        $iAvailable = $item->getAvailable();
        $iPickupLocation = $item->getPickupLocation();
        $iReturnLocation = $item->getReturnLocation();
        $iStartDate = $item->getBorrowStartDate();
        $iEndDate = $item->getBorrowEndDate();
        $timestamp = $item->getBidEndDate();
        $iImage = $item->getItemImage();

        if ($timestamp != NULL) {
            date_default_timezone_set("Asia/Singapore");
            $date = date('Y-m-d', strtotime($timestamp));
            $hour = date('H', strtotime($timestamp));
            $min = date('i', strtotime($timestamp));
            $sec = date('s', strtotime($timestamp));
        }
        ?>

        <div class="row">
            <form method="POST" class="form" role="form" enctype="multipart/form-data" action='<?php $_SERVER['REQUEST_URI'] ?>'>
                <input class="hidden" value="<?php echo $iId ?>" name="itemid" id="itemid"/>
                <input class="hidden" value="<?php echo $iAvailable ?>" name="iavailable" id="iavailable"/>
                <br> 
                <div class="form-group ">
                    <div class="fileinput fileinput-new" data-provides="fileinput" data-name="fileToUpload">
                        <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"><img src="uploadFiles/<?php echo $iImage ?>"></div>
                    </div>
                </div>

                <div class="form-group ">
                    <input class="form-control" id="item_title" name="item_title" required type="text" value="<?php echo $iTitle ?>">
                </div>
                <div class="form-group ">
                    <textarea class="form-control" rows="5" id="description" name="description"><?php echo htmlspecialchars($iDesc) ?></textarea>
                </div>

                <div class="form-group">
                    <select class="form-control" id="category" name="category">
                        <option value="appliances" <?php
                        if ($iCategory === 'appliances') {
                            echo("selected");
                        }
                        ?>>Appliances</option>
                        <option value="book" <?php
                        if ($iCategory === 'book') {
                            echo("selected");
                        }
                        ?> >Book</option>
                        <option value="furniture" <?php
                        if ($iCategory === 'furniture') {
                            echo("selected");
                        }
                        ?>>Furniture</option>
                        <option value="tool" <?php
                        if ($iCategory === 'tool') {
                            echo("selected");
                        }
                        ?> >Tool</option>
                        <option value="others" <?php
                        if ($iCategory === 'others') {
                            echo("selected");
                        }
                        ?> >Others</option>
                    </select>
                </div>

                <div class="form-group">
                    <div class="controls form-inline">
                        <span class="black">Fee For Lending: </span> 
                        <select class="form-control" id="bid_point_status" name="bid_point_status">
                            <option value="no" <?php
                            if ($iBidPoint === 0) {
                                echo("selected");
                            }
                            ?>>No</option>
                            <option value="yes" <?php
                            if ($iBidPoint > 0) {
                                echo("selected");
                            }
                            ?>>Yes</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class='input-group date hidden' id="bidDate">
                        <input class="form-control" id="bid_end_date" name="bid_end_date" required type="text" value="<?php echo $date ?>">
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="form-group">
                        <div class="control-group">
                            <div class="controls form-inline hidden" id="bid_timming">
                                <span class="black">Hour : </span> 
                                <select class="form-control" id="hours" name="hours">
                                    <?php
                                    for ($i = 0; $i < 24; $i++) {
                                        if ($i === $hour) {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "' selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        } else {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="black">Minute : </span> 
                                <select class="form-control" id="minutes" name="minutes">
                                    <?php
                                    for ($i = 0; $i < 60; $i++) {
                                        if ($i === $min) {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "' selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        } else {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <span class="black">Second : </span> 
                                <select class="form-control" id="seconds" name="seconds">
                                    <?php
                                    for ($i = 0; $i < 60; $i++) {
                                        if ($i === $sec) {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "' selected>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        } else {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                    </div> 
                </div>

                <div class="form-group ">
                    <input class="form-control hidden" id="min_bid_point" name="min_bid_point" required type="integer" value="<?php echo $iBidPoint ?>">
                </div>

                <div class="form-group ">
                    <input class="form-control" id="pickup_location" name="pickup_location" required type="text" value="<?php echo $iPickupLocation ?>">
                </div>

                <div class="form-group ">
                    <input class="form-control" id="return_location" name="return_location" required type="text" value="<?php echo $iReturnLocation ?>">
                </div>

                <div class="form-group">
                    <input class="form-control" id="borrow_start_date" name="borrow_start_date" required type="text" value="<?php echo $iStartDate ?>">
                </div>

                <div class="form-group">
                    <input class="form-control" id="borrow_end_date" name="borrow_end_date" required type="text" value="<?php echo $iEndDate ?>">
                </div>

               <input class="btn btn-success btn-lg btn-block" id="submit" name="submit" type="submit" value="Update">
            </form>
            <br/>
        </div>
    </div>

    <?php
}
$content = ob_get_clean();
include_once 'template/skeleton.php';
?>
