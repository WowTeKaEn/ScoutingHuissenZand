<?php

class db {
    private static $instance = null;

    private function __construct() {
        try {
            $this->pdo = new PDO($_ENV["DATABASE_URL"], $_ENV["DATABESE_USER"], $_ENV["DATABASE_PASS"]);
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
            return $prepare->fetchAll(PDO::FETCH_ASSOC);
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
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
}

return db::getInstance();

?>