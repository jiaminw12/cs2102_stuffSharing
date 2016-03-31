<?php

namespace BorrowController {

    include_once __DIR__ . '/../model/Borrow.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/UserController.php';

    function createNewBorrow($item_id, $status) {
        date_default_timezone_set("Asia/Singapore");

        $owner = $item_id->getOwner();
        $borrower = $_SESSION['username'];
        $statement = "INSERT INTO borrows (owner, borrower, item_id, bid_point) VALUES ({$owner}, '{$borrower}', {$item_id}', {$status}')";
        $result = \DBHandler::execute($statement, false);

        if (!$result) {
            return null;
        } else {
            return new \Borrow($owner, $borrower, $item_id, $status, $created_date);
        }
    }

    function getAllBorrows() {
        $executingUser = isset($_SESSION['username']) ? \UserController\getUser($_SESSION['username']) : null;
        if ($executingUser == null || $executingUser->getAdmin() != 0) {
            return null;
        }

        $statement = "SELECT * FROM borrows";
        $result = \DBHandler::execute($statement, true);

        $borrowList = array();
        foreach ($result as $res) {
            $borrowList[] = new \Borrow($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return $borrowList;
    }

    function removeBorrow($item_id, $borrower) {
        if (\UserController\canActiveUserModifyBorrow($item_id)) {
            $statement = "DELETE FROM borrows WHERE item_id = '{$item_id}' AND borrower = '{$borrower}'";
            $result = \DBHandler::execute($statement, false);
            return $result;
        } else {
            return null;
        }
    }

}
?>
