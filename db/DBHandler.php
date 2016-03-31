<?php

class DBHandler {

    public $dbh;

    public function __construct() {
        global $config;
        try {
            $dbuser = 'postgres';
            $dbpass = 'Password01';
            $host = 'localhost';
            $dbname = 'db_Stuff';
            $this->dbh = pg_pconnect("host=localhost port=5432 dbname=db_Stuff user=postgres password=Password01");
        } catch (PDOException $e) {
            echo "Error : " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function close() {
        pg_close();
    }

    public static function execute($query, $needResult) {
        $instance = new DBHandler();
        $stid = pg_query($instance->dbh, $query);
        if ($needResult) {
            // SELECT
            if (!$stid) {
                $errormessage = pg_last_error($instance->dbh);
                echo "Error with query: " . $errormessage;
                exit();
            } else {
                $result = array();
                while (($row = pg_fetch_row($stid))) {
                    $result[] = $row;
                }
                $instance->close();
                unset($instance);
                return $result;
            }
        } else {
            // INSERT, UPDATE
            if (!$stid) {
                $errormessage = pg_last_error($instance->dbh);
                echo "Error with query: " . $errormessage;
                exit();
            }
        }

        $instance->close();
        unset($instance);
    }

}
?>

