<?php

namespace BidController {

    include_once __DIR__ . '/../model/Bid.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/../helper/DateHelper.php';
    include_once __DIR__ . '/UserController.php';

    function createNewContribution($item_title, $bid_point) {
        date_default_timezone_set("Asia/Singapore");

        $owner = $item_title->getOwner();
        $borrower = $_SESSION['username'];
        $bid_date = \DateHelper\convertToSqlFormatFromUnixTime(time());

        $statement = "INSERT INTO bid (owner, borrower, itemiD, bid_date, bid_point) VALUES ({$owner}, '{$borrower}', {$itemiD}, '{$bid_date}', {$bid_point}')";
        $result = \DBHandler::execute($statement, false);

        if (!$result) {
            return null;
        } else {
            return new \Bid($owner, $borrower, $itemiD, $bid_date, $bid_point);
        }
    }

    function getAllContribution() {
        $executingUser = isset($_SESSION['username']) ? \UserController\getUser($_SESSION['username']) : null;
        if ($executingUser == null || $executingUser->getRoles() != 'admin') {
            return null;
        }

        $statement = "SELECT * FROM bid";
        $result = \DBHandler::execute($statement, true);

        $contributionList = array();
        foreach ($result as $res) {
            $res[3] = \DateHelper\beautifyDateFromSql($res[3]);
            $bidList[] = new \Bid($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return $bidList;
    }

    function removeBid($item_title, $borrower) {
        if (\UserController\canActiveUserModifyBid($itemiD)) {
            $statement = "DELETE FROM bid WHERE itemiD = {$itemiD} AND borrower = {$borrower}";
            $result = \DBHandler::execute($statement, false);
            return $result;
        } else {
            return null;
        }
    }

}
?>
