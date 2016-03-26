<?php

namespace UserController {

    include_once __DIR__ . '/../db/DBHandler.php';
    include_once __DIR__ . '/../model/User.php';

//include_once __DIR__ . '/ContributionController.php';
//include_once __DIR__ . '/ProjectController.php';

    function signIn($username, $password) {
        $user = getUser($username);
        if (isset($user) && $user->verifyPassword($password)) {
            $_SESSION["username"] = $user->getUsername();
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

    function createNewUser($username, $name, $email, $password, $contact_num, $address, $date_of_birth, $admin) {
        $password = md5($password);

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
            return null;
        } else {

            $statement = "INSERT INTO userinfo(username, name, email, password, contact_num, address, date_of_birth, admin) VALUES('" . $username . "', '" . $name . "', '" . $email . "', '" . $password . "', '" . $contact_num ."', '" . $address ."', '" . $date_of_birth . "', '" . $admin . "')";

            $r = \DBHandler::execute($statement, false);
            
            // redirect if login was successful
            header('Location:' . 'index.php');
        }
    }

    function getUser($username) {
        $statement = "SELECT * FROM userinfo WHERE username ='" .  $username ."'";
        $result = \DBHandler::execute($statement, true);
        
        if (count($result) != 1) {
            return null;
        } else {
            $result = $result[0];
            return new \User($result[0], $result[1], $result[2], $result[3], $result[4], $result[5], $result[6], $result[7], $result[8]);
        }
    }

    function getActiveUser() {
        $activeUser = isset($_SESSION['username']) ? $_SESSION['username'] : null;
        if ($activeUser) {
            $activeUser = getUser($activeUser);
        }
        return $activeUser;
    }

    function isCreator($username) {
        if (isset($username)) {
            $statement = "SELECT admin FROM userinfo WHERE username = '$username'";
            $result = \DBHandler::execute($statement, true);

            if (count($result) != 1) {
                return false;
            } else {
                $result = $result[0];
                return $result[7] == 0 || $result[7] == 1;
            }
        } else {
            return false;
        }
    }

    function isAdmin($username) {
        if (isset($username)) {
            $statement = "SELECT * FROM userinfo WHERE username = '{$username}'";
            $result = \DBHandler::execute($statement, true);

            return count($result) == 1 && $result[0][7] == 1;
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
            $userList[] = new \User($res['USERNAME'], $res['NAME'], $res['ROLES'], $res['EMAIL'], $res['PASSWORD']);
        }

        return $userList;
    }

    function canActiveUserModifyUser($username) {
        $activeUser = getActiveUser();
        $userToModify = getUser($username);
        return $activeUser && $userToModify && $activeUser->canModifyUser($userToModify);
    }

    function canActiveUserModifyProject($projectId) {
        $activeUser = getActiveUser();
        $projectToModify = \ProjectController\getProject($projectId);
        return $activeUser && $projectToModify && $activeUser->canModifyProject($projectToModify);
    }

    function canActiveUserModifyContribution($contributionId) {
        $activeUser = getActiveUser();
        $contributionToModify = \ContributionController\getContribution($contributionId);
        return $activeUser && $contributionToModify && $activeUser->canModifyContribution($contributionToModify);
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
