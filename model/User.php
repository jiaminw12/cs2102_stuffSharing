<?php

include_once __DIR__ . '/Bid.php';
include_once __DIR__ . '/../db/DBHandler.php';
include_once __DIR__ . '/Item.php';

date_default_timezone_set("Asia/Singapore");

class User {

    private $name;
    private $admin;
    private $email;
    private $password;
    private $contact_num;
<<<<<<< HEAD
    private $bid_point;

    private function save() {
        $statement = "UPDATE userinfo SET name='{$this->name}', email='{$this->email}', password='{$this->password},bid_point='{$this->bid_point}' contact_num='{$this->contact_num}',admin='{$this->admin}'' WHERE username='{$this->username}'";
        return DBHandler::execute($statement, false);
    }

    public function __construct($username, $email, $name, $password, $contact_num, $admin, $bit_point) {
=======

    private function save() {
        $statement = "UPDATE userinfo SET name='{$this->name}', email='{$this->email}', password='{$this->password}, contact_num='{$this->contact_num}',admin='{$this->admin}'' WHERE username='{$this->username}'";
        return DBHandler::execute($statement, false);
    }

    public function __construct($username, $email, $name, $password, $contact_num, $admin) {
>>>>>>> origin/master
        $this->username = $username;
        $this->email = $email;
        $this->name = $name;
        $this->password = $password;
        $this->contact_num = $contact_num;
<<<<<<< HEAD
        $this->bid_point = $bit_point;
=======
>>>>>>> origin/master
        $this->admin = $admin;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
        return $this->save();
    }

    public function getAdmin() {
        return $this->roles;
    }

    public function setAdmin($admin) {
        $this->admin = $admin;
        return $this->save();
    }

    public function getEmail() {
        return $this->email;
    }
<<<<<<< HEAD
    public function getBidPoint() {
        return $this->bid_point;
    }
=======
>>>>>>> origin/master

    public function setEmail($email) {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) === true) {
            return null;
            $oldEmail = $this->email;
            $this->email = $email;
            $success = $this->save();
            if (!$success) {
                $this->email = $oldEmail;
                return null;
            } else {
                return true;
            }
        } else {
            return null;
        }
    }

    //$password -> password in plaintext
    public function setPassword($password) {
        $this->password = md5($password);
        return $this->save();
    }

    //$guess -> guess in plaintext
    public function verifyPassword($guess) {
        $guess = md5($guess);
        return $this->password == $guess;
    }

    public function getContactNum() {
        return $this->contact_num;
    }

    public function setContactNum($contact_num) {
        $this->contact_num = $contact_num;
        return $this->save();
    }
<<<<<<< HEAD
    
    public function setBidPoint($bid_point) {
        $this->bid_point = $bid_point;
        return $this->save();
    }
=======
>>>>>>> origin/master
}

?>
