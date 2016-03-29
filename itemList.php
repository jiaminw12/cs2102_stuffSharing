<?php
session_start();
$current_page = 'Items';
include_once "controller/UserController.php";
include_once "controller/ItemController.php";
include_once "controller/CategoryController.php";

$default_page = "all";
$page = $default_page;

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

if (isset($_GET['min_bid'])) {
    $min_bid = $_GET['min_bid'];
    if ($min_bid < 0 || is_float($min_bid)) {
        $message = "Enter numerical amount";
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
            $errors[] = "extension not allowed, please choose a JPEG or PNG file.";
        }

        if ($file_size > 2097152) {
            $errors[] = 'File size must be excately 2 MB';
        }
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

//        $item_title = $_POST["item_title"];
//        $description = $_POST["description"];
//        $category = $_POST["category"];
//        $min_bid = $_POST["min_bid"];
//        $pickup_location = $_POST["pickup_location"];
//        $return_location = $_POST["return_location"];
//        $borrow_start_date = $_POST["borrow_start_date"];
//        $borrow_end_date = $_POST["borrow_end_date"];
//        $bid_end_date = $_POST["bid_end_date"];
//        $item_image = $file_name;
//        if (ItemController\createNewItem($item_title, $description, $category, $min_bid, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image)) {
        move_uploaded_file($file_tmp, "uploadFiles/" . $file_name);
//            $message = "New item added";
//            $message_type = "success";
//        } else {
//
//            $message = "Enter numerical amount";
//            $message_type = "danger";
//        }
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
                        <h4 class = "text-left"> <?php echo $item->getTitle(); ?>     
                            <button type="button" class="btn btn-success btn-xs">
                                <a href="itemList.php?page=<?php echo $item->getCategory(); ?>">
                                    <?php echo $item->getCategory(); ?>
                                </a>
                            </button></h4>
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
                            <input class="form-control" id="title" name="title" placeholder="Title" required type="text" value="">
                        </div>
                        <div class="form-group ">
                            <textarea class="form-control" rows="10" id="description" name="description" placeholder="Description" ></textarea>
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
                            <div class='input-group date'>
                                <input class="form-control" id="bid_end_date" name="bid_end_date" placeholder="Bid End Date" required type="text">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                            </div>
                        </div>
                        <?php
                        $timestamp = date('d M Y h:i A', time() + 900);
                        ?>

                        <div class="form-group ">
                            <div class='input-group date' id='min_bid' >
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-usd"></span>
                                </span>
                                <input type='text' class="form-control" name="min_bid" value="0" />
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
