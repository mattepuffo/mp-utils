<?php

/**
 * Class Connessione
 *
 * @version 2.1
 * @since 2020-09-10
 */

class Connessione {

    private $pdo;
    private $userDb = "";
    private $passwordDb = "";
    private $hostDb = "";
    private $nameDb = "";
    private static $instance;

    private function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=" . $this->hostDb . ";dbname=" . $this->nameDb, $this->userDb, $this->passwordDb, array(
                PDO::ATTR_PERSISTENT => TRUE,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET lc_time_names='it_IT', NAMES utf8"
            ));
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public static function getInstance() {
        if (!isset(self::$instance)) {
            $c = __CLASS__;
            self::$instance = new $c;
        }
        return self::$instance;
    }

    public function __clone() {
        trigger_error('Clone is not allowed', E_USER_ERROR);
    }

    public function execQuery($cmd) {
        try {
            $result = $this->pdo->query($cmd);
            return $result;
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function execQueryPrepare($cmd, $arrayCampi) {
        try {
            $prepare = $this->pdo->prepare($cmd);
            $prepare->execute($arrayCampi);
            return $prepare->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function execPrepare($cmd, $arrayCampi) {
        try {
            $prepare = $this->pdo->prepare($cmd);
            $prepare->execute($arrayCampi);
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function execPrepareLastId($cmd, $arrayCampi) {
        try {
            $prepare = $this->pdo->prepare($cmd);
            $prepare->execute($arrayCampi);
            return $this->pdo->lastInsertId();
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

    public function execQueryLogin($cmd, $arrayCampi) {
        try {
            $cmd = $this->pdo->prepare($cmd);
            $cmd->execute($arrayCampi);
            if ($cmd->rowCount() == 1) {
                return $cmd->fetchAll();
            } else {
                return FALSE;
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }

}
