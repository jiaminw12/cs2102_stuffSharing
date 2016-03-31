<?php

namespace BidController {

    include_once __DIR__ . '/../model/Bid.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/UserController.php';

    function createNewBid($item_id, $bid_point) {
        date_default_timezone_set("Asia/Singapore");

        $owner = $item_id->getOwner();
        $bidder = $_SESSION['username'];
        $statement = "INSERT INTO bids (owner, bidder, item_id, bid_point) VALUES ({$owner}, '{$bidder}', {$item_id}', {$bid_point}')";
        $result = \DBHandler::execute($statement, false);

        if (!$result) {
            return null;
        } else {
            return new \Bid($owner, $bidder, $item_id, $bid_point);
        }
    }

    function getAllBids() {
        $executingUser = isset($_SESSION['username']) ? \UserController\getUser($_SESSION['username']) : null;
        if ($executingUser == null || $executingUser->getAdmin() != 0) {
            return null;
        }

        $statement = "SELECT * FROM bids";
        $result = \DBHandler::execute($statement, true);

        $bidList = array();
        foreach ($result as $res) {
            $bidList[] = new \Bid($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return $bidList;
    }

    function removeBid($item_id, $borrower) {
        if (\UserController\canActiveUserModifyBid($item_id)) {
            $statement = "DELETE FROM bids WHERE item_id = '{$item_id}' AND borrower = '{$borrower}'";
            $result = \DBHandler::execute($statement, false);
            return $result;
        } else {
            return null;
        }
    }

}
?>