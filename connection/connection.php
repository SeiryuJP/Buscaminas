<?php
    include_once 'credentials.php';
    include_once './class/player.php';

    class Connection{
        private static $connection;
        private static $query;
        private static $result;

        static function startConnection(){
            try {
                self::$connection = new mysqli(Credentials::$hostname, Credentials::$user, Credentials::$passwd, Credentials::$db);
            }
            catch (Exception $exception){
                return 'Fallo al conectar a MySQL: ('.$exception->getMessage().')';
                die();
            }
        }

        static function closeConnection(){
            self::$connection->close();
        }

        static function getAllPlayers(){
            self::$query = "SELECT * FROM " . Credentials::$tablePlayers;
            self::startConnection();
            if (self::$result = self::$connection->query(self::$query)){
                while ($row = self::$result->fetch_assoc()){
                    $player = new Player($row["Name"], $row["Password"], $row["Wins"], $row["Losses"]);
                    $playersArray[] = $player;
                }
            }
            self::$result->free_result();
            self::closeConnection();
            return $playersArray;
        }

        static function getSpecificPlayer($name){
            self::$query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            self::$result = $stmt->get_result();
            while ($row = self::$result->fetch_assoc()){
                $player = new Player($row["Name"], $row["Password"], $row["Wins"], $row["Losses"]);
            }
            self::$result->free_result();
            self::closeConnection();
            return $player;
        }

        static function login($name, $password){
            self::$query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            self::$result = $stmt->get_result();
            while ($row = self::$result->fetch_assoc()){
                $player = new Player($row["Name"], $row["Password"], $row["Wins"], $row["Losses"]);
            }
            self::$result->free_result();
            self::closeConnection();

            if ($player->getPassword() === $password){
                return $player;
            }
            else {
                return false;
            }
        }

        static function signUp($name, $password){
            self::$query = "INSERT INTO ".Credentials::$tablePlayers." (Name, Password, Wins, Losses) VALUES (?, ?, ?, ?, ?)";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $wins = 0;
            $losses = 0;
            $stmt->bind_param("ssss", $name, $password, $wins, $losses);
            $stmt->execute();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
            self::closeConnection();
        }

        static function updatePlayer($name, $wins, $losses){
            self::$query = "UPDATE ".Credentials::$tablePlayers ." SET Wins = ?, Losses = ? WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $stmt->bind_param("sss", $wins, $losses, $name);
            $stmt->execute();
            if ($stmt->affected_rows){
                $edited = true;
            }
            else {
                $edited = false;
            }
            self::closeConnection();
            return $edited;
        }
    }
?>