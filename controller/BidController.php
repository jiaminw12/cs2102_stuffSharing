<?php

namespace BidController {

    include_once __DIR__ . '/../model/Bid.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/UserController.php';

    function createNewBid($item_id, $bid_point) {
        date_default_timezone_set("Asia/Singapore");

        $owner = $item_id->getOwner();
        $bidder = $_SESSION['username'];
        $statement = "INSERT INTO bids (owner, bidder, item_id, bid_point) VALUES ('{$owner}', '{$bidder}', ' {$item_id}', ' {$bid_point}')";
        $result = \DBHandler::execute($statement, false);

        if (!$result) {
            return null;
        } else {
            return new \Bid($owner, $bidder, $item_id, $bid_point);
        }
    }

    function getAllBids() {

        $statement = "SELECT * FROM bids";
        $result = \DBHandler::execute($statement, true);

        $bidList = array();
        foreach ($result as $res) {
            $bidList[] = new \Bid($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return $bidList;
    }
    
    function getSelectedBids($item_id) {
        $statement = "SELECT * FROM bids WHERE item_id='" . $item_id . "'";
        $result = \DBHandler::execute($statement, true);

        $bidList2 = array();
        foreach ($result as $res) {
            $bidList2[] = new \Bid($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return $bidList2;
    }

    // Get the bid record for user
    function getSelectedBidByUser($bidder) {
        $statement = "SELECT * FROM bids WHERE bidder = '" . $bidder . "'";
        $result = \DBHandler::execute($statement, true);

        $bidList = array();
        foreach ($result as $res) {
            $bidList[] = new \Bid($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return $bidList;
    }

    // Get the bid record for user based on item_id 
    function getSelectedBidByUserAndItemID($bidder, $item_id) {
        $statement = "SELECT * FROM bids WHERE bidder = '" . $bidder . "' AND item_id='" . $item_id . "'";
        $result = \DBHandler::execute($statement, true);

        $bidList = $result[0];
        $bidList = new \Bid($result[0], $result[1], $result[2], $result[3], $result[4]);

        return $bidList;
    }

    // To check whether "BIDS" contains any highest bid_point based on the item_id
    function getSelectedBidBoolean($item_id) {
        $statement = "SELECT * FROM bids WHERE item_id='" . $item_id . "'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) >= 1) {
            return true;
        } else {
            return false;
        }
    }

    function getTheHighestBidPoint($item_id) {
        $statement = "SELECT bidder, MAX(bid_point) FROM bids WHERE item_id='" . $item_id . "'";
        $result = \DBHandler::execute($statement, true);
        $bidList = array();
            foreach ($result as $res) {
                $bidList[] = $res;
            }
        return $bidList;
    }

    // Update the bidPoint based on the latest highest bid points
    function updateBidPoint($owner, $bidder, $item_id, $bid_point) {
        $statement = "UPDATE bids SET bid_point='{$bid_point}'WHERE owner='" . $owner . "'AND item_id='" . $item_id . "' AND bidder='" . bidder . "'";
        return DBHandler::execute($statement, false);
    }

    //  User remove their bid
    function removeBidByUser($item_id, $bidder) {
        if (\UserController\canActiveUserModifyBid($item_id)) {
            $statement1 = \BidController\getSelectedBidByUser($bidder);
            $result1 = \DBHandler::execute($statement1, false);
            $owner = $result[0];
            $bid_point = $result1[0];

            $statement3 = \UserController\recalculateBidPoint($owner, $bid_point);
            $result2 = \DBHandler::execute($statement3, false);

            $statement2 = "DELETE FROM bids WHERE item_id = '{$item_id}' AND bidder = '{$bidder}'";
            $result = \DBHandler::execute($statement2, false);

            return $result;
        } else {
            return null;
        }
    }

    function removeAllBidsByItemID($item_id, $owner) {
        // return the highest point
        $statement1 = \BidController\getTheHighestBidPoint($item_id);
        $result1 = \DBHandler::execute($statement1, false);
        $bidder = $result1[0];
        $highest_bid_point = $result1[1];
        $status = 1;

        // insert into borrow table
        $statement2 = \BorrowController\createNewBorrow($owner, $bidder, $item_id, $status);
        \DBHandler::execute($statement2, false);

        // delete, return the points
        // update the points into successful sharing -> owner?
        $statement3 = "SELECT * FROM bids WHERE item_id='" . $item_id . "'";
        $result3 = \DBHandler::execute($statement3, true);
        foreach ($result3 as $res) {
            if ($res[3] < $highest_bid_point) {
                $statement4 = \UserController\recalculateBidPoint($res[0], $res[3]);
                $result4 = \DBHandler::execute($statement4, false);
                $statement = "DELETE FROM bids WHERE item_id='" . $item_id . "' AND bidder='" . $res[1] . "'";
                \DBHandler::execute($statement, false);
            }
        }
        
        \ItemController\updateAvailable($item_id, 0);        
    }

}
?>