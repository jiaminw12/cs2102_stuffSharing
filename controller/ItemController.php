<?php

namespace ItemController {

include_once __DIR__ . '/../model/Item.php';
include_once __DIR__ . '/../db/DBHandler.php';
include_once __DIR__ . '/UserController.php';
include_once __DIR__ . '/../helper/ArrayHelper.php';

function createNewItem($item_title, $description, $category, $min_bid, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image) {
  date_default_timezone_set("Asia/Singapore");

  $owner = $_SESSION['username'];
  $statement = "INSERT INTO items(owner, item_title, description, category, min_bid, pickup_location, return_location, borrow_start_date, borrow_end_date, bid_end_date, item_image) VALUES( '{$owner}', '{$item_title}', '{$description}', '{$category}', '{$min_bid}', '{$pickup_location}', '{$return_location}', '{$borrow_start_date}', '{$borrow_end_date}', '{$bid_end_date}', '{$item_image}')";
  $r = \DBHandler::execute($statement, false);
    return new \Item($owner, $item_title, $description, $category, $min_bid, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image);
}

function getItem($itemiD) {
  $statement = "SELECT * FROM items WHERE itemID= {$itemiD}";

  $result = \DBHandler::execute($statement, true);

  if (count($result) != 1) {
    return null;
  } else {
    $result = $result[0];
    
    $result[9] = \DateHelper\beautifyDateFromSql($result[9]);
    
    return new \Item($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6], $result[7], $result[8], $result[9], $result[10], $result[11]);
  }
}

function getAllItems() {
  $statement = "SELECT * FROM items ORDER BY item_id DESC";

  $result = \DBHandler::execute($statement, true);

  $projects = array();
  foreach ($result as $res) {
    $items[] = new \Item($res[0], $res[1], $res[2], $res[3], $res[4], $res[5], $res[6], $res[7], $res[8], $res[9], $res[10], $res[11]);
  }

  return $items;
}

function getActiveUserItem() {
  $activeUser = \UserController\getSignedInUser();
  if (!isset($activeUser)) {
    return null;
  } else {
    return $activeUser->getItemList();
  }
}

function removeItem($itemiD) {
  if (\UserController\canActiveUserModifyItem($itemiD)) {
    $statement = "DELETE FROM items WHERE itemiD = {itemiD}";
    $result = \DBHandler::execute($statement, false);
    return $result;
  } else {
    return null;
  }
}

}

?>
