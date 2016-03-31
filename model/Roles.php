<?php

include_once __DIR__ . '/../db/DBHandler.php';
include_once __DIR__ . '/User.php';

class Roles {
  public static $ADMIN = "admin";
  public static $USER= "user";

  public static function getUserList($roles) {
    $statement = "SELECT * FROM userinfo WHERE admin='{$roles}'";
    $result = DBHandler::execute($statement, true);

    $users = array();
    foreach ($result as $res) {
      $users[] = new User($res[0], $res[1], $res[2], $res[3], $res[4], $res[5], $res[6], $res[7], $res[8]);
    }

    return $users;
  }

}

?>
