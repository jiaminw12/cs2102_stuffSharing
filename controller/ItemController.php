<?php

namespace ItemController {

    include_once __DIR__ . '/../model/Item.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/UserController.php';

    function createNewItem($item_id, $item_title, $description, $category, $bid_point_status, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image) {
        date_default_timezone_set("Asia/Singapore");
        $owner = getUserEmail(($_SESSION['username']));

        if ($bid_end_date != "NULL") {
            $statement = "INSERT INTO items(item_id, owner, item_title, description, category, bid_point_status, pickup_location, return_location, borrow_start_date, borrow_end_date, bid_end_date, item_image) VALUES( '{$item_id}', '{$owner}', '{$item_title}', '{$description}', '{$category}', '{$bid_point_status}', '{$pickup_location}', '{$return_location}', '{$borrow_start_date}', '{$borrow_end_date}', '{$bid_end_date}', '{$item_image}')";
        } else {
            $statement = "INSERT INTO items(item_id, owner, item_title, description, category, bid_point_status, pickup_location, return_location, borrow_start_date, borrow_end_date, item_image) VALUES( '{$item_id}', '{$owner}', '{$item_title}', '{$description}', '{$category}', '{$bid_point_status}', '{$pickup_location}', '{$return_location}', '{$borrow_start_date}', '{$borrow_end_date}', '{$item_image}')";
        }

        $r = \DBHandler::execute($statement, false);
        if (!$r) {
            return null;
        } else {
            $available = 1;
            return new \Item($item_id, $owner, $item_title, $description, $category, $bid_point_status, $available, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date, $item_image);
        }
    }

    function getItem($item_id) {
        $statement = "SELECT * FROM items WHERE item_id LIKE '{$item_id}'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return NULL;
        } else {
            $result = $result[0];
            $items[] = new \Item($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6], $result[7], $result[8], $result[9], $result[10], $result[11], $result[12]);
            return $items;
        }
    }

    function getAllItems() {
        $statement = "SELECT * FROM items WHERE available = 1 ORDER BY bid_end_date DESC";
       
        $result = \DBHandler::execute($statement, true);
        $itemList = array();
        foreach ($result as $res) {
            $itemList[] = new \Item($res[0], $res[1], $res[2], $res[3], $res[4], $res[5], $res[6], $res[7], $res[8], $res[9], $res[10], $res[11], $res[12]);
        }
        return $itemList;
    }

    function getSelectedItems($category) {
        $statement = "SELECT * FROM items WHERE category = '" . $category . "'AND available = 1 ORDER BY item_id DESC";

        $result = \DBHandler::execute($statement, true);

        $projects = array();
        foreach ($result as $res) {
            $items[] = new \Item($res[0], $res[1], $res[2], $res[3], $res[4], $res[5], $res[6], $res[7], $res[8], $res[9], $res[10], $res[11], $res[12]);
        }
        return $items;
    }

    function getAvailableItem($owner) {
        $statement = "SELECT * FROM items WHERE owner = '" . $owner . "' ORDER BY item_id DESC";

        $result = \DBHandler::execute($statement, true);

        $projects = array();
        foreach ($result as $res) {
            $items[] = new \Item($res[0], $res[1], $res[2], $res[3], $res[4], $res[5], $res[6], $res[7], $res[8], $res[9], $res[10], $res[11], $res[12]);
        }
        return $items;
    }

    function getUserEmail($username) {
        $statement = "SELECT * FROM userinfo WHERE username ='" . $username . "'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return NULL;
        } else {
            $result = $result[0];
            return $result[1];
        }
    }

    function getItemMinBidPoint($itemid) {
        $statement = "SELECT bid_point_status FROM items WHERE item_id ='" . $itemid . "'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return NULL;
        } else {
            $result = $result[0];
            return $result[0];
        }
    }

    function getActiveUserItem() {
        $activeUser = \UserController\getSignedInUser();
        if (!isset($activeUser)) {
            return null;
        } else {
            return $activeUser->getItemList();
        }
    }

    function getItemIDByDate($bid_end_date) {
        $statement = "SELECT item_id FROM items WHERE bid_end_date ='" . $bid_end_date . "'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return NULL;
        } else {
            $result = $result[0];
            return $result[0];
        }
    }

    function updateItemDetails($item_id, $item_title, $description, $bid_point_status, $available, $pickup_location, $return_location, $borrow_start_date, $borrow_end_date, $bid_end_date) {
        if ($bid_end_date != "NULL") {
            $statement = "UPDATE items SET item_title='" . $item_title . "', description ='" . $description . "', bid_point_status=" . $bid_point_status . ", available=" . $available . ", pickup_location='" . $pickup_location . "', return_location='" . $return_location . "', borrow_start_date='" . $borrow_start_date . "', borrow_end_date='" . $borrow_end_date . "', bid_end_date='" . $bid_end_date . "' WHERE item_id='" . $item_id . "'";
        } else {
            $statement = "UPDATE items SET item_title='" . $item_title . "', description ='" . $description . "', bid_point_status=" . $bid_point_status . ", available=" . $available . ", pickup_location='" . $pickup_location . "', return_location='" . $return_location . "', borrow_start_date='" . $borrow_start_date . "', borrow_end_date='" . $borrow_end_date . "', bid_end_date=" . $bid_end_date . " WHERE item_id='" . $item_id . "'";
        }

        \DBHandler::execute($statement, false);
    }

    function updateAvailable($item_id, $available) {
        $statement = "UPDATE items SET available=" . $available . " WHERE item_id ='" . $item_id . "'";
        \DBHandler::execute($statement, false);
    }

    function removeItem($item_id) {
        //   if (\UserController\canActiveUserModifyItem($item_id)) {
        $statement = "DELETE FROM items WHERE item_id = '" . $item_id . "'";
        \DBHandler::execute($statement, false);
        header("Location: profile.php");
        //    } else {
        //        header("Location: profile.php");
        //    }
    }

    function searchItem($searchKeyword) {
        $searchKeyword = strtoupper($searchKeyword);
        if (!empty($searchKeyword)) {
            $statement = "SELECT DISTINCT item_id, item_title, description, item_image
                  FROM items
                  WHERE UPPER(item_title) LIKE '%" . $searchKeyword . "%' OR
                        UPPER(description) LIKE '%" . $searchKeyword . "%'";

            $result = \DBHandler::execute($statement, true);

            $projects = array();
            foreach ($result as $res) {
                $projects[] = $res;
            }
            return $projects;
        } else {
            return NULL;
        }
    }
    
    function searchItemCategory($searchKeyword) {
        if (!empty($searchKeyword)) {
            $statement = "SELECT DISTINCT item_id, item_title, description, item_image
                  FROM items
                  WHERE category LIKE '" .strtolower($searchKeyword)  ."'";

            $result = \DBHandler::execute($statement, true);

            $projects = array();
            foreach ($result as $res) {
                $projects[] = $res;
            }
            return $projects;
        } else {
            return NULL;
        }
    }

}

?>