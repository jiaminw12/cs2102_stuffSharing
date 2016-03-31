<?php

include_once __DIR__ . '/../db/DBHandler.php';
include_once __DIR__ . '/Bid.php';
include_once __DIR__ . '/User.php';

date_default_timezone_set("Asia/Singapore");

class Item {

    private $item_id;
    private $owner;
    private $title;
    private $description;
    private $category;
    private $bid_point_status;
    private $available;
    private $pickup_location;
    private $return_location;
    private $borrow_start_date;
    private $borrow_end_date;
    private $bid_end_date;
    private $item_image;

    private function save() {
        $statement = "UPDATE items SET owner='{$this->owner}', item_title='{$this->item_title}', description='{$this->description}', category='{$this->category}', bid_point_status='{$this->bid_point_status}', ', available='{$this->available}', pickup_location='{$this->pickup_location}', return_location='{$this->return_location}', borrow_start_date='{$this->borrow_start_date}', borrow_end_date='{$this->borrow_end_date}', bid_end_date='{$this->bid_end_date}', item_image='{$this->item_image}' WHERE item_id='{$this->item_id}'";
        return DBHandler::execute($statement, false);
    }

    public function __construct($item_id, $owner, $item_title, $description, $category, $bid_point_status, $available, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image) {
<<<<<<< HEAD
        echo $item_image;
        $thid->item_id = $item_id;
=======
        $this->item_id = $item_id;
>>>>>>> origin/master
        $this->owner = $owner;
        $this->item_title = $item_title;
        $this->description = $description;
        $this->category = $category;
        $this->bid_point_status = $bid_point_status;
        $this->avaiable = $available;
        $this->pickup_location = $pickup_location;
        $this->return_location = $return_location;
        $this->borrow_start_date = $borrow_start_date;
        $this->borrow_end_date = $borrow_end_date;
        $this->bid_end_date = $bid_end_date;
        $this->item_image = $item_image;
    }
    
    public function getItemId() {
        return $this->item_id;
    }
    
    public function getItemTitle() {
        return $this->item_title;
    }

    public function getOwner() {
        return $this->owner;
    }

<<<<<<< HEAD
    public function getTitle() {
        return $this->title;
    }

=======
>>>>>>> origin/master
    public function getDescription() {
        return $this->description;
    }

    public function getCategory() {
        return $this->category;
    }

    public function getBidPointStatus() {
        return $this->bid_point_status;
    }
    
     public function getAvailable() {
        return $this->avaiable;
    }

    public function getPickupLocation() {
        return $this->pickup_location;
    }

    public function getReturnLocation() {
        return $this->return_location;
    }

    public function getBorrowStartDate() {
        return $this->borrow_start_date;
    }

    public function getBorrowEndDate() {
        return $this->borrow_end_date;
    }

    public function getBidEndDate() {
        return $this->bid_end_date;
    }

    public function getItemImage() {
        return $this->item_image;
    }
    
    public function setItemId($item_id){
        $this->item_id = $item_id;
        return $this->save();
    }
    
    public function setOwner($owner) {
        $this->owner = $owner;
        return $this->save();
    }

<<<<<<< HEAD
    public function setTitle($title) {
        $this->title = $title;
=======
    public function setItemTitle($item_title) {
        $this->item_title = $item_title;
>>>>>>> origin/master
        return $this->save();
    }

    public function setDescription($description) {
        $this->description = $description;
        return $this->save();
    }

    public function setCategory($category) {
        $this->category = $category;
        return $this->save();
    }
    
    public function setBidPointStatus($bid_point_status) {
        $this->bid_point_status = $bid_point_status;
        return $this->save();
    }
    
    public function setAvailable($available) {
        $this->available = $available;
        return $this->save();
    }

    public function setPickupLocation($pickup_location) {
        $this->pickup_location = $pickup_location;
        return $this->save();
    }

    public function setReturnLocation($return_location) {
        $this->return_location = $return_location;
        return $this->save();
    }

    public function setBorrowStartDate($borrow_start_date) {
        $this->borrow_start_date = $borrow_start_date;
        return $this->save();
    }

    public function setBorrowEndDate($borrow_end_date) {
        $this->borrow_end_date = $borrow_end_date;
        return $this->save();
    }

    public function setBidEndDate($bid_end_date) {
        $this->bid_end_date = $bid_end_date;
        return $this->save();
    }

    public function setItemImage($item_image) {
        echo $item_image;
        $this->item_image = $item_image;
        return $this->save();
    }

    public function isItemOpen() {
        return time() <= strtotime($this->bid_end_date);
    }

    public function getBidList() {
        $statement = "SELECT * FROM bid WHERE item_id = {$this->item_id} ORDER BY bid_date DESC";

        $result = DBHandler::execute($statement, true);

        $bids= array();
        foreach ($result as $res) {
            $res[3] = \DateHelper\beautifyDateFromSql($res[3]);
            $bids[] = new Bid($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return bids;
    }

    public function getBidPoint() {
        $statement = "SELECT MAX(bid_point) FROM bid WHERE WHERE item_id= {$this->item_id}";

        $result = DBHandler::execute($statement, true);

        if (isset($result[0])) {
            return $result[0];
        } else {
            return 0;
        }
    }

}

?>
