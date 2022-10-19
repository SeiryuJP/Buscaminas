<?php
    include_once 'credentials.php';
    include_once './class/player.php';
    include_once './class/field.php';

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

        static function getSpecificPlayer($name, $password){
            self::$query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Name = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $stmt->bind_param("ss", $name, $password);
            $stmt->execute();
            self::$result = $stmt->get_result();
            while ($row = self::$result->fetch_assoc()){
                $player = new Player($row["Name"], '', $row["Wins"], $row["Losses"]);
            }
            self::$result->free_result();
            self::closeConnection();
            return $player;
        }

        static function signUp($name, $password){
            self::$query = "INSERT INTO ".Credentials::$tablePlayers." (Name, Password, Wins, Losses) VALUES (?, ?, ?, ?)";
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

        static function updatePlayer($name, $password, $wins, $losses){
            self::$query = "UPDATE ".Credentials::$tablePlayers ." SET Wins = ?, Losses = ? WHERE Name = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $stmt->bind_param("ssss", $wins, $losses, $name, $password);
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

        static function getSpecificField($name){
            self::$query = "SELECT * FROM ".Credentials::$tableFields." WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            self::$result = $stmt->get_result();
            while ($row = self::$result->fetch_assoc()){
                $visibleFieldString = $row['Field_visible'];
                $visibleField = explode(',', $visibleFieldString);
                $hiddenFieldString = $row['Field_hidden'];
                $hiddenField = explode(',', $hiddenFieldString);
                $field = new Field($row["Name"], $row['Size'], $visibleField, $hiddenField);
            }
            self::$result->free_result();
            self::closeConnection();
            return $field;
        }

        static function createField($field){
            self::$query = "INSERT INTO ".Credentials::$tableFields." (Name, Size, Field_visible, Field_hidden) VALUES (?, ?, ?, ?)";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $user = $field->getName();
            $size = $field->getSize();
            $visibleField = $field->getVisibleField();
            $visibleFieldString = implode(',', $visibleField);
            $hiddenField = $field->getHiddenField();
            $hiddenFieldString = implode(',', $hiddenField);
            $stmt->bind_param("ssss", $user, $size, $visibleFieldString, $hiddenFieldString);
            $stmt->execute();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
            self::closeConnection();
            return $field;
        }

        static function updateField($field, $user){
            self::$query = "UPDATE ".Credentials::$tableFields ." SET Field_visible = ?, Field_hidden = ? WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $visibleField = $field->getVisibleField();
            $visibleFieldString = implode(',', $visibleField);
            $hiddenField = $field->getHiddenField();
            $hiddenFieldString = implode(',', $hiddenField);
            $stmt->bind_param("sss", $visibleFieldString, $hiddenFieldString, $user);
            $stmt->execute();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
            self::closeConnection();
        }

        static function updateNewField($field, $user){
            self::$query = "UPDATE ".Credentials::$tableFields ." SET Size = ?, Field_visible = ?, Field_hidden = ? WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $size = $field->getSize();
            $visibleField = $field->getVisibleField();
            $visibleFieldString = implode(',', $visibleField);
            $hiddenField = $field->getHiddenField();
            $hiddenFieldString = implode(',', $hiddenField);
            $stmt->bind_param("ssss", $size, $visibleFieldString, $hiddenFieldString, $user);
            $stmt->execute();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
            self::closeConnection();
        }
    }
?>