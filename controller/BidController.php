<?php

namespace BidController {

    include_once __DIR__ . '/../model/Bid.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/UserController.php';
    include_once __DIR__ . '/ItemController.php';
    include_once __DIR__ . '/BorrowController.php';

    function createNewBid($owner, $bidder, $item_id, $bid_point) {
        date_default_timezone_set("Asia/Singapore");

        $statement = "INSERT INTO bids (owner, bidder, item_id, bid_point) VALUES ('{$owner}', '{$bidder}', '{$item_id}', '{$bid_point}')";
        $result = \DBHandler::execute($statement, false);

        if (!$result) {
            return NULL;
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

    // Get the bid record for user based on item_id and current_user
    function getSelectedBidByUserAndItemID($bidder, $item_id) {
        $statement = "SELECT bid_point FROM bids WHERE bidder='" . $bidder . "' AND item_id='" . $item_id . "'";
        $result = \DBHandler::execute($statement, true);
        if (count($result) == 1) {
            $bidList = $result[0];
            return $bidList;
        } else {
            return NULL;
        }
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
        $statement = "SELECT MAX(bid_point) FROM bids WHERE item_id='" . $item_id . "'";
        $result = \DBHandler::execute($statement, true);
        $bidList = array();
        foreach ($result as $res) {
            $bidList[] = $res;
        }
        return $bidList;
    }

    function getTheHighestBidPointReturnUser($item_id) {
        $statement = "SELECT owner, bidder, MAX(bid_point) FROM bids WHERE item_id='" . $item_id . "' GROUP BY bidder, owner";
        $result = \DBHandler::execute($statement, true);
        $bidList = array();
        foreach ($result as $res) {
            $bidList[] = $res;
        }
        return $bidList;
    }

    // Update the bidPoint based on the latest highest bid points
    function updateBidPoint($owner, $bidder, $item_id, $bid_point) {
        $statement = "UPDATE bids SET bid_point=" . $bid_point . " WHERE owner='" . $owner . "' AND item_id='" . $item_id . "' AND bidder='" . $bidder . "'";
        $RESULT = \DBHandler::execute($statement, false);
        print_r($RESULT);
        header("Location: profile.php");
        return TRUE;
    }

    //  User remove their bid
    function removeBidByUser($item_id, $bidder) {
        if (\UserController\canActiveUserModifyBid($item_id)) {
            $statement1 = \BidController\getSelectedBidByUser($bidder);
            $result1 = \DBHandler::execute($statement1, false);
            $owner = $result[0];
            $bid_point = $result1[0];

            $statement3 = \UserController\updateUserBidPoint($owner, $bid_point);
            $result2 = \DBHandler::execute($statement3, false);

            $statement2 = "DELETE FROM bids WHERE item_id = '{$item_id}' AND bidder = '{$bidder}'";
            $result = \DBHandler::execute($statement2, false);

            return $result;
        } else {
            return NULL;
        }
    }

    function removeBidByItemID($item_id) {
        $statement1 = "DELETE FROM bids WHERE item_id = '" . $item_id . "'";
        \DBHandler::execute($statement1, false);
        return TRUE;
    }

    function removeAllBidsByItemID() {
        // get the yesterday bid_end_date
        $s1 = "SELECT item_id FROM items WHERE bid_end_date < now()::date + interval '1h'";
        $r1 = \DBHandler::execute($s1, true);
        if (count($r1) >= 1) {
            foreach ($r1 as $expiredDate) {
                $itemid = $expiredDate[0];
                // return the highest point
                $s2 = \BidController\getTheHighestBidPoint($itemid);
                if ($s2 != NULL) {
                    $highestPoint = $s2[0][0];

                    $s3 = \BidController\getTheHighestBidPointReturnUser($itemid);
                    foreach ($s3 as $result) {
                        $owner = $result[0];
                        $bidder = $result[1];
                        $point = $result[2];
                        $r4 = \UserController\getUserBidPoint(\UserController\getUsername($bidder));
                        if ($point !== $highestPoint) {
                            \UserController\updateUserBidPoint((\UserController\getUsername($bidder)), $r4[0]+$point);
                        } else {
                            \BorrowController\createNewBorrow($owner, $bidder, $itemid, 1);
                            $r5 = \ItemController\getItemMinBidPoint($itemid);
                            \UserController\updateUserBidPoint((\UserController\getUsername($bidder)), $r4[0]+$r5);
                        }
                    }
                    \BidController\removeBidByItemID($itemid);
                    \ItemController\updateAvailable($itemid, 0);
                }
            }
        }
    }

}
?>