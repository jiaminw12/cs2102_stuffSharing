<?php
session_start();
$current_page = 'Items';
include_once "controller/UserController.php";
include_once "controller/ItemController.php";
include_once "controller/CategoryController.php";

$default_page = "all";
$page = $default_page;

function crypto_rand_secure($min, $max) {
    $range = $max - $min;
    if ($range < 1)
        return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd >= $range);
    return $min + $rnd;
}

function getToken($length) {
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet) - 1;
    for ($i = 0; $i < $length; $i++) {
        $token .= $codeAlphabet[crypto_rand_secure(0, $max)];
    }
    return $token;
}

if (isset($_GET['page'])) {
    $page = $_GET['page'];
}

if (isset($_GET['item_title'])) {
    $item_title = $_GET['item_title'];
    $item = ItemController\getItem($item_title);
    if (!isset($item)) {
        $message = "Item Title " . $item_title . " not found";
        $message_type = "danger";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = array();
    $file_name = $_FILES['image']['name'];
    $file_size = $_FILES['image']['size'];
    $file_tmp = $_FILES['image']['tmp_name'];
    $file_type = $_FILES['image']['type'];
    $file_ext = strtolower(end(explode('.', $_FILES['image']['name'])));

    if ($file_size != 0) {
        $expensions = array("jpeg", "jpg", "png");
        if (in_array($file_ext, $expensions) === false) {
            $errors[] = "Extension not allowed, please choose a JPEG or PNG file.";
        }
        if ($file_size > 5000000) {
            $errors[] = 'File size must be excately 5 MB';
        }
    }

    $item_id = getToken(30);
    $item_title = $_POST["item_title"];
    $description = $_POST["description"];
    $category = $_POST["category"];
    $bid_point_status = $_POST["bid_point_status"];
    
    if ($bid_point_status == "yes"){
        $bid_point_status = 1;
        $bid_end_date = $_POST["bid_end_date"];
        $time = $_POST["hours"] . ":" . $_POST["minutes"] . ":" . $_POST["seconds"];
    } else {
        $bid_point_status = 0;
        $bid_end_date = "0000-00-00";
        $time = "00::00::00";
    }
    
    $pickup_location = $_POST["pickup_location"];
    $return_location = $_POST["return_location"];
    $borrow_start_date = $_POST["borrow_start_date"];
    $borrow_end_date = $_POST["borrow_end_date"];

    if ($borrow_start_date < $bid_end_date || $borrow_end_date < $bid_end_date) {
        $errors[] = "Borrow end date must be after borrow start date.";
    }

    if ($borrow_end_date - $borrow_start_date < 0) {
        $errors[] = "Borrow end date must be after borrow start date.";
    }

    if (empty($errors) == true) {
        if (is_null($file_name)) {
            $file_name = "default.jpg";
        } else {
            // get the file extension first
            $ext = substr(strrchr($file_name, "."), 1);
            // make the random file name
            $randName = md5(rand() * time());
            // and now we have the unique file name for the upload file
            $file_name = $randName . '.' . $ext;
        }
        $bid_end_date = $bid_end_date . " " . $time;
        $item_image = $file_name;
        $items = ItemController\createNewItem($item_id, $item_title, $description, $category, $bid_point_status, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image);
        move_uploaded_file($file_tmp, "uploadFiles/" . $file_name);
        $message = "New item added";
        $message_type = "success";
    } else {
        print_r($errors);
    }
}
?>

<?php ob_start(); ?>

<script type="text/javascript">

    $(document).ready(function() {
        $('#bid_end_date').datepicker({});
        $('#borrow_start_date').datepicker({});
        $('#borrow_end_date').datepicker({});

        $("#bid_point_status").change(function() {
            $(this).find("option:selected").each(function() {
                if ($(this).attr("value") == "yes") {
                    $("#bidDate").removeClass('hidden');
                    $("#bid_timming").removeClass('hidden');
                } else {
                    $("#bidDate").addClass('hidden');
                    $("#bid_timming").addClass('hidden');
                }
            });
        }).change();

    });

</script>

<br/>
<?php if (!isset($item)) { ?>
    <div class="inner cover container">
        <div class="row">
            <?php
            include_once 'template/message.php';
            ?>
            <div class="row"> 
                <ul class="nav nav-tabs" role="tablist">
                    <li role="presentation" class="active"><a href="#all" aria-controls="all" role="tab" data-toggle="tab">All</a></li>
                    <li role="presentation"><a href="#appliances" aria-controls="appliances" role="tab" data-toggle="tab">Appliances</a></li>
                    <li role="presentation"><a href="#book" aria-controls="book" role="tab" data-toggle="tab">Book</a></li>
                    <li role="presentation"><a href="#furniture" aria-controls="furniture" role="tab" data-toggle="tab">Furniture</a></li>
                    <li role="presentation"><a href="#tool" aria-controls="tool" role="tab" data-toggle="tab">Tool</a></li>
                    <li role="presentation"><a href="#others" aria-controls="others" role="tab" data-toggle="tab">Others</a></li>

                    <?php
                    if (UserController\isSignedIn() && UserController\isCreator($_SESSION["username"])) {
                        ?>
                        <li role="presentation" style="float:right;"><a href="#addNewItem" aria-controls="addNewItem" role="tab" data-toggle="tab">Create Item</a></li>
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="tab-content">

        <div role="tabpanel" class="tab-pane fade in active" id="all">
            <?php
            $itemList = ItemController\getAllItems();
            foreach ($itemList as $item) {
                ?>
                <div class="row">
                    <hr/>
                    <div class="col-md-6">
                        <h4 class = "text-left"> <?php echo $item->getItemTitle(); ?>
                            <img src="uploadFiles/<?php echo $item->getItemImage(); ?>" class="img-responsive" alt="" style="height: 150px;">
                            <button type="button" class="btn btn-success btn-xs">
                                <a href="itemList.php?page=<?php echo $item->getCategory(); ?>">
                                    <?php echo $item->getCategory(); ?>
                                </a>
                            </button></h4>
                    </div>
                </div>
            <?php } ?>
        </div>

        <div role="tabpanel" class="tab-pane fade" id="appliances">...</div>
        <div role="tabpanel" class="tab-pane fade" id="book">...</div>
        <div role="tabpanel" class="tab-pane fade" id="furniture">...</div>
        <div role="tabpanel" class="tab-pane fade" id="tool">...</div>
        <div role="tabpanel" class="tab-pane fade" id="others">...</div>

        <?php
        if (UserController\isSignedIn() && UserController\isCreator($_SESSION["username"])) {
            ?>
            <div role="tabpanel" class="tab-pane fade" id="addNewItem">
                <form method="POST" class="form" role="form" enctype="multipart/form-data">
                    <br> 
                    <div class="form-group ">
                        <div class="fileinput fileinput-new" data-provides="fileinput" data-name="fileToUpload">
                            <div class="fileinput-preview thumbnail" data-trigger="fileinput" style="width: 200px; height: 150px;"><img src="uploadFiles/default.jpg"></div>
                            <div>
                                <span class="btn btn-default btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input type="file" name="image"></span>
                                <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">Remove</a>
                            </div>
                        </div>
                    </div>

                    <div class="form-group ">
                        <input class="form-control" id="item_title" name="item_title" placeholder="Title" required type="text" value="">
                    </div>
                    <div class="form-group ">
                        <textarea class="form-control" rows="5" id="description" name="description" placeholder="Description" ></textarea>
                    </div>

                    <div class="form-group">
                        <select class="form-control" id="category" name="category">
                            <option value="appliances">Appliances</option>
                            <option value="book">Book</option>
                            <option value="furniture">Furniture</option>
                            <option value="tool">Tool</option>
                            <option value="others" selected>Others</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <div class="controls form-inline">
                            <span class="black">Free For Lending: </span> 
                            <select class="form-control" id="bid_point_status" name="bid_point_status">
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class='input-group date hidden' id="bidDate">
                            <input class="form-control" id="bid_end_date" name="bid_end_date" placeholder="Bid End Date" required type="text">
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
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="black">Minute : </span> 
                                    <select class="form-control" id="minutes" name="minutes">
                                        <?php
                                        for ($i = 0; $i < 61; $i++) {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        }
                                        ?>
                                    </select>
                                    <span class="black">Second : </span> 
                                    <select class="form-control" id="seconds" name="seconds">
                                        <?php
                                        for ($i = 0; $i < 61; $i++) {
                                            echo "<option value='" . str_pad($i, 2, '0', STR_PAD_LEFT) . "'>" . str_pad($i, 2, '0', STR_PAD_LEFT) . "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div> 
                    </div>

                    <div class="form-group ">
                        <input class="form-control" id="pickup_location" name="pickup_location" placeholder="Pickup Location" required type="text">
                    </div>

                    <div class="form-group ">
                        <input class="form-control" id="return_location" name="return_location" placeholder="Return Location" required type="text">
                    </div>

                    <div class="form-group">
                        <input class="form-control" id="borrow_start_date" name="borrow_start_date" placeholder="Borrow Start Date" required type="text">
                    </div>

                    <div class="form-group">
                        <input class="form-control" id="borrow_end_date" name="borrow_end_date" placeholder="Borrow End Date" required type="text">
                    </div>

                    <button class="btn btn-success btn-lg btn-block" id="submit" name="submit" type="buttom">Submit</button>
                </form>


            </div>                <?php
        }
        ?>
    </div>

    <?php
    $content = ob_get_clean();
    include_once 'template/skeleton.php';
    ?>
