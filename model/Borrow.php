<?php

include_once __DIR__ . '/../db/DBHandler.php';

class Borrow {

    private $owner;
    private $borrower;
    private $item_id;
    private $status;
    private $created_date;

    private function save() {
        $statement = "UPDATE borrows SET status={$this->status} ' WHERE item_id={$this->item_id} ' AND ' borrower='{$this->borrower} ' AND ' owner='{$this->owner}";
        return DBHandler::execute($statement, false);
    }

    public function __construct($owner, $borrower, $item_id, $status, $created_date) {
        $this->owner = $owner;
        $this->borrower = $borrower;
        $this->item_id = $item_id;
        $this->status = $status;
        $this->created_date = $created_date;
    }

    public function getOwner() {
        return $this->owner;
    }

    public function getBidder() {
        return $this->borrower;
    }

    public function getItemId() {
        return $this->item_id;
    }

    public function getStatus() {
        return $this->status;
    }
    
    public function getCreatedDate() {
        return $this->created_date;
    }

    public function setOwner($owner) {
        $this->owner = $owner;
        return $this->save();
    }

    public function setBidder($borrower) {
        $this->borrower = $borrower;
        return $this->save();
    }

    public function setItemId($item_id) {
        $this->item_id = intval($item_id);
        return $this->save();
    }

    public function setStatus($status) {
        $this->status = $status;
        return $this->save();
    }
    
        public function setCreatedDate($created_date) {
        $this->created_date = $created_date;
        return $this->save();
    }

}

?>
