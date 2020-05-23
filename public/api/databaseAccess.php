<?php
require_once "functions.php";

class db {
    private static $instance = null;

    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=id12852262_scouting", "id12852262_webuser", "tuxKVQmroauoNUUF");
        } catch (Exception $e) {
            http_response_code(500);
            die(print_r($e->getMessage()));
        }
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new db();
        }
        return self::$instance;
    }

    function executeQuery($query){
        try {
            $prepare = $this->pdo->query(strip_tags($query));
            return $prepare->fetchAll();
        } catch (Exception $e) {
            http_response_code(500);
            die(print_r($e->getMessage()));
        }
    }

    function preparedQuery($prepared, $execute) {
        try {
            $stmt = $this->pdo->prepare($prepared);
            foreach ($execute as $execution => $key) {
                $stmt->bindValue($execution + 1, strip_tags($key));
            }
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (Exception $e) {
            http_response_code(500);
            die(print_r($e->getMessage()));
        }
    }

    function preparedInsert($prepared, $execute) {
        try {
            $stmt = $this->pdo->prepare($prepared);
            foreach ($execute as $execution => $key) {
                $stmt->bindValue($execution + 1, strip_tags($key));
            }
            if($stmt->execute()){
                return $stmt->rowCount();
            }else{
                return false;
            }
        } catch (Exception $e) {
            http_response_code(500);
            die(print_r($e->getMessage()));
        }
    }

    function quote($string) {
        return str_replace("'", "", $this->pdo->quote($string));
    }

}

?>