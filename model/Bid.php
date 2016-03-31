<?php

include_once __DIR__ . '/../db/DBHandler.php';
include_once __DIR__ . '/../helper/DateHelper.php';

class Bid {

    private $owner;
    private $bidder;
    private $item_id;
    private $bid_point;
    private $created_date;

    private function save() {
        $statement = "UPDATE bid SET bid_point={$this->bid_point} ' WHERE item_id={$this->item_id} ' AND ' bidder='{$this->bidder} ' AND ' owner='{$this->owner}";
        return DBHandler::execute($statement, false);
    }

    public function __construct($owner, $bidder, $item_id, $bid_point, $created_date) {
        $this->owner = $owner;
        $this->bidder = $bidder;
        $this->item_id = $item_id;
        $this->bid_point = $bid_point;
        $this->created_date = $created_date;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getBidder() {
        return $this->bidder;
    }

    public function getItemId() {
        return $this->item_id;
    }

    public function getBidPoint() {
        return $this->bid_point;
    }
    
    public function getCreatedDate() {
        return $this->created_date;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
        return $this->save();
    }

    public function setBidder($bidder) {
        $this->bidder = $bidder;
        return $this->save();
    }

    public function setItemId($item_id) {
        $this->item_id = intval($item_id);
        return $this->save();
    }

    public function setBidPoint($bid_point) {
        $this->bid_point = $bid_point;
        return $this->save();
    }
    
        public function setCreatedDate($created_date) {
        $this->created_date = $created_date;
        return $this->save();
    }

}

?>