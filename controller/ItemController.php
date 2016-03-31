<?php

namespace ItemController {

    include_once __DIR__ . '/../model/Item.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/../model/User.php';

    function createNewItem($item_id, $item_title, $description, $category, $bid_point_status, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image) {
        date_default_timezone_set("Asia/Singapore");

        $owner = getUserEmail(($_SESSION['username']));
        $statement = "INSERT INTO items(item_id, owner, item_title, description, category, bid_point_status, pickup_location, return_location, borrow_start_date, borrow_end_date, bid_end_date, item_image) VALUES( '{$item_id}', '{$owner}', '{$item_title}', '{$description}', '{$category}', '{$bid_point_status}', '{$pickup_location}', '{$return_location}', '{$borrow_start_date}', '{$borrow_end_date}', '{$bid_end_date}', '{$item_image}')";
        $r = \DBHandler::execute($statement, false);
        $available = 1;
        return new \Item($item_id, $owner, $item_title, $description, $category, $bid_point_status, $available, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image);
    }

    function getItem($item_id) {
        $statement = "SELECT * FROM items WHERE item_id= {$item_id}";

        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return null;
        } else {
            $result = $result[0];

            return new \Item($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6], $result[7], $result[8], $result[9], $result[10], $result[11], $result[12]);
        }
    }

    function getAllItems() {
        $statement = "SELECT * FROM items ORDER BY item_id DESC";

        $result = \DBHandler::execute($statement, true);

        $projects = array();
        foreach ($result as $res) {
            $items[] = new \Item($res[0], $res[1], $res[2], $res[3], $res[4], $res[5], $res[6], $res[7], $res[8], $res[9], $res[10], $res[11], $res[12]);
        }

        return $items;
    }
    
    function getUserEmail($username){
        $statement = "SELECT * FROM userinfo WHERE username ='" .  $username ."'";
        $result = \DBHandler::execute($statement, true);
        
        if (count($result) != 1) {
            return null;
        } else {
            $result = $result[0];
            return $result[1];
        }
    }

    function getAvailableUserItem($owner) {
        $statement = "SELECT * FROM  items WHERE owner ='" .  $owner ."'";
        $result = \DBHandler::execute($statement, true);
        
        if (count($result) != 1) {
            return null;
        } else {
            $result = $result[0];
            return new \Item($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6], $result[7], $result[8], $result[9], $result[10], $result[11], $result[12]);
        }
    }

    function removeItem($item_id) {
        if (\UserController\canActiveUserModifyItem($item_id)) {
            $statement = "DELETE FROM items WHERE item_id = {$item_id}";
            $result = \DBHandler::execute($statement, false);
            return $result;
        } else {
            return null;
        }
    }

}

?>
