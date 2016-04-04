<?php

namespace BorrowController {

    include_once __DIR__ . '/../model/Borrow.php';
    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/UserController.php';

    function createNewBorrow($owner, $borrower, $item_id, $status) {
        date_default_timezone_set("Asia/Singapore");
        $statement = "INSERT INTO borrows(owner, borrower, item_id, status) VALUES ('{$owner}', '{$borrower}', '{$item_id}', '{$status}')";
        $result = \DBHandler::execute($statement, false);

        if (!$result) {
            return NULL;
        } else {
            return new \Borrow($owner, $borrower, $item_id, $status);
        }
    }

    function getAllBorrows() {
        $statement = "SELECT * FROM borrows";
        $result = \DBHandler::execute($statement, true);

        $borrowList = array();
        foreach ($result as $res) {
            $borrowList[] = new \Borrow($res[0], $res[1], $res[2], $res[3], $res[4]);
        }

        return $borrowList;
    }

    function getAllBorrowsByUser($borrower) {
        $statement = "SELECT * FROM borrows WHERE borrower='" . $borrower . "'";
        $result = \DBHandler::execute($statement, true);
        $borrowList = array();
        foreach ($result as $res) {
            $borrowList[] = new \Borrow($res[0], $res[1], $res[2], $res[3]);
        }
        return $borrowList;
    }

    function updateStatus($owner, $borrower, $item_id, $status) {
        $statement = "UPDATE borrows SET status='{$status}'WHERE owner='" . $owner . "'AND item_id='" . $item_id . "' AND borrower='" . $borrower . "'";
        return \DBHandler::execute($statement, false);
    }

    function removeBorrow($owner, $borrower, $item_id) {
     //   if (\UserController\canActiveUserModifyBorrow($item_id)) {
            $statement = "DELETE FROM borrows WHERE owner='" . $owner . "'AND item_id='" . $item_id . "' AND borrower='" . $borrower . "'";
            \DBHandler::execute($statement, false);
            header("Location: profile.php");
    //        return $result;
    //    } else {
    //        return null;
    //    }
    }

    function removeAllBorrows() {
        $statement = "DELETE FROM borrows";
        $result = \DBHandler::execute($statement, false);
        return $result;
    }

}
?>
