<?php
    include_once 'credentials.php';
    include_once './class/player.php';
    include_once './class/field.php';

    class Connection{
        private static $connection;

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
            $query = "SELECT * FROM " . Credentials::$tablePlayers;
            self::startConnection();
            if ($result = self::$connection->query($query)){
                while ($row = $result->fetch_assoc()){
                    $player = new Player($row["Name"], $row["Password"], $row["Wins"], $row["Losses"]);
                    $playersArray[] = $player;
                }
            }
            $result->free_result();
            self::closeConnection();
            return $playersArray;
        }

        static function getSpecificPlayer($name, $password){
            $query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Name = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("ss", $name, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()){
                $player = new Player($row["Name"], '', $row["Wins"], $row["Losses"]);
            }
            $result->free_result();
            self::closeConnection();
            return $player;
        }

        static function signUp($name, $password){
            $query = "INSERT INTO ".Credentials::$tablePlayers." (Name, Password, Wins, Losses) VALUES (?, ?, ?, ?)";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
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
            $query = "UPDATE ".Credentials::$tablePlayers ." SET Wins = ?, Losses = ? WHERE Name = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
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
            $query = "SELECT * FROM ".Credentials::$tableFields." WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("s", $name);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()){
                $visibleFieldString = $row['Field_visible'];
                $visibleField = explode(',', $visibleFieldString);
                $hiddenFieldString = $row['Field_hidden'];
                $hiddenField = explode(',', $hiddenFieldString);
                $field = new Field($row["Name"], $row['Size'], $visibleField, $hiddenField);
            }
            $result->free_result();
            self::closeConnection();
            return $field;
        }

        static function createField($field){
            $query = "INSERT INTO ".Credentials::$tableFields." (Name, Size, Field_visible, Field_hidden) VALUES (?, ?, ?, ?)";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
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
            $query = "UPDATE ".Credentials::$tableFields ." SET Field_visible = ?, Field_hidden = ? WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
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
            $query = "UPDATE ".Credentials::$tableFields ." SET Size = ?, Field_visible = ?, Field_hidden = ? WHERE Name = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
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