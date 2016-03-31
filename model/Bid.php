<?php

include_once __DIR__ . '/../db/DBHandler.php';
include_once __DIR__ . '/../helper/DateHelper.php';

class Bid {
    
  private $owner;
  private $borrower;
  private $item_title;
  private $bid_date;
  private $bid_point;

  private function save() {
    $this->bid_date = \DateHelper\convertToSqlFormatFromString($this->bid_date);
    $statement = "UPDATE bid SET $bid_date='{$this->$bid_date}, bid_point={$this->bid_point} ' WHERE item_title={$this->item_title} ' AND ' borrower='{$this->borrower} ' AND ' owner='{$this->owner}";
    $this->bid_date = \DateHelper\beautifyDateFromSql($this->bid_date);
    return DBHandler::execute($statement, false);
  }

  public function __construct($owner, $borrower, $item_title, $bid_date, $bid_point) {
    $this->owner = $owner;
    $this->borrower = $borrower;
    $this->item_title = $item_title;
    $this->bid_date = $bid_date;
    $this->bid_point = $bid_point;
  }

  public function getOwner() {
    return $this->owner;
  }

  public function getBorrower() {
    return $this->borrower;
  }

  public function getItemTitle() {
    return $this->item_title;
  }

  public function getDate() {
    return $this->$bid_date;
  }

  public function getBidPoint() {
    return $this->bid_point;
  }

  public function setBorrower($borrower) {
    $this->borrower = $borrower;
    return $this->save();
  }

  public function setItemTitle($item_title) {
    $this->item_title = intval($item_title);
    return $this->save();
  }

  public function setBidDate($bid_date) {
    $this->bid_date = $bid_date;
    return $this->save();
  }

  public function setBidPoint($bid_point) {
    $this->bid_point = $bid_point;
    return $this->save();
  }
  
}
?>
