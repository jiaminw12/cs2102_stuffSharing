<?php

namespace UserController {

    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/../model/User.php';
    include_once __DIR__ . '/BidController.php';
    include_once __DIR__ . '/ItemController.php';

    function signIn($username, $password) {
        $user = getUser($username);
        if (isset($user) && $user->verifyPassword($password)) {
            $_SESSION["username"] = $user->getUsername();
            echo $_SESSION["username"];
            return true;
        } else {
            return false;
        }
    }
    

    function signOut() {
        unset($_SESSION["username"]);
    }

    function isSignedIn() {
        if ($_SESSION["username"]) {
            return true;
        } else {
            return false;
        }
    }

    function getSignedInUser() {
        if (!isset($_SESSION['username'])) {
            return null;
        } else {
            return getUser($_SESSION['username']);
        }
    }

    function createNewUser($username, $email, $name, $password, $contact_num, $admin) {
        $password = md5($password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
            return null;
        } else {
            $statement = "INSERT INTO userinfo(username, email, name, password, contact_num, admin) VALUES('" . $username . "', '" . $email . "', '" . $name . "', '" . $password . "', '" . $contact_num . "', '" . $admin . "')";

            $r = \DBHandler::execute($statement, false);

            // redirect if login was successful
            header('Location:' . 'index.php');
        }
    }
    
   // function updateProfile($username, $name, $contact_num, $password, $email) {
    //    $statement = "UPDATE unserinfo SET username='" . $username . "', name ='" . $name . "', contact_num='" . $contact_num . "', password='" . "' WHERE email = '" .$email ."'";
     //   return DBHandler::execute($statement, false);
   // }

    function getUser($username) {
        $statement = "SELECT * FROM userinfo WHERE username ='" . $username . "'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return null;
        } else {
            $result = $result[0];
            return new \User($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6]);
        }
    }

    function getUsername($email) {
        $statement = "SELECT username FROM userinfo WHERE email ='" . $email . "'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return null;
        } else {
            $result = $result[0];
            return $result[0];
        }
    }
    
    function getEmail($username) {
        $statement = "SELECT email FROM userinfo WHERE username ='" . $username . "'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return null;
        } else {
            $result = $result[0];
            return $result[0];
        }
    }

    function getActiveUser() {
        $activeUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        if ($activeUser) {
            $activeUser = getUser($activeUser);
        }
        return $activeUser;
    }

    function isCreator($owner, $item_id) {
        $statement = "SELECT * FROM items WHERE owner = '$owner' AND item_id = '$item_id'";
        $result = \DBHandler::execute($statement, true);

        if (count($result) != 1) {
            return false;
        } else {
            return true;
        }
    }

    function isAdmin($username) {
        if (isset($username)) {
            $statement = "SELECT * FROM userinfo WHERE username = '" . $username . "' AND admin = 1";
            $result = \DBHandler::execute($statement, true);

            return count($result) == 1 && $result[0][5] == 1;
        } else {
            return false;
        }
    }

    function getAllUser() {
        $executingUser = isset($_SESSION['username']) ? getUser($_SESSION['username']) : null;
        if ($executingUser == null || $executingUser->getRoles() != 'admin') {
            return null;
        }

        $statement = "SELECT * FROM userinfo";
        $result = \DBHandler::execute($statement, true);

        $userList = array();
        foreach ($result as $res) {
            $userList[] = new \User($res[0], $res[1], $res[2], $res[3], $res[4], $res[5], $res[6]);
        }

        return $userList;
    }

    function UpdateBidPoint($owner, $bid_point) {
        $statement = "UPDATE userinfo SET bid_point='" . $bid_point . "'WHERE owner='" . $owner . "'";
        return DBHandler::execute($statement, false);
    }

    function canActiveUserModifyUser($username) {
        $activeUser = getActiveUser();
        $userToModify = getUser($username);
        return $activeUser && $userToModify && $activeUser->canModifyUser($userToModify);
    }

    function canActiveUserModifyItem($itemiD) {
        $activeUser = getActiveUser();
        $itemToModify = \ItemController\getProject($itemiD);
        return $activeUser && $itemToModify && $activeUser->canModifyProject($itemtToModify);
    }

    function canActiveUserModifyBid($bid) {
        $activeUser = getActiveUser();
        $bidToModify = \BidController\getBid($itemID);
        return $activeUser && $bidToModify && $activeUser->canModifyBid($bidToModify);
    }

    function recalculateBidPoint($username, $bid_point) {
        $statement = "UPDATE userinfo SET bid_point='".$bid_point."'WHERE username='". $username. "'";
        return DBHandler::execute($statement, false);
    }

    function removeUser($username) {
        if (canActiveUserModifyUser($username)) {
            $statement = "DELETE FROM userinfo WHERE username = '{$username}'";
            $result = \DBHandler::execute($statement, false);
            return $result;
        } else {
            return null;
        }
    }

}

?>