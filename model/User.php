<?php

include_once __DIR__ . '/Bid.php';
include_once __DIR__ . '/../db/DBHandler.php';
include_once __DIR__ . '/../helper/DateHelper.php';
include_once __DIR__ . '/Item.php';

date_default_timezone_set("Asia/Singapore");

class User {

    private $username;
    private $name;
    private $admin;
    private $email;
    private $password;
    private $address;
    private $contact_num;
    private $date_of_birth;

    private function save() {
        $statement = "UPDATE userinfo SET name='{$this->name}', email='{$this->email}', password='{$this->password}, contact_num='{$this->contact_num}', address='{$this->address}', date_of_birth='{$this->date_of_birth}', admin='{$this->admin}'' WHERE username='{$this->username}'";
        return DBHandler::execute($statement, false);
    }

    public function __construct($username, $name, $email, $password, $contact_num, $address, $date_of_birth, $admin) {
        $this->username = $username;
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
        $this->contact_num = $contact_num;
        $this->address = $address;
        $this->date_of_birth = $date_of_birth;
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

    public function getAddress() {
        return $this->address;
    }

    public function setAddress($address) {
        $this->address = $address;
        return $this->save();
    }
    
}

?>
