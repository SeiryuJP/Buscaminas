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

        static function getSpecificPlayer($id, $password){
            $query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Id = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("ss", $id, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()){
                $player = new Player($row["Id"], $row['Name'], $row["Wins"], $row["Losses"]);
            }
            $result->free_result();
            self::closeConnection();
            return $player;
        }

        static function signUp($id, $name, $password){
            $query = "INSERT INTO ".Credentials::$tablePlayers." (Id, Name, Password, Wins, Losses) VALUES (?, ?, ?, ?, ?)";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $wins = 0;
            $losses = 0;
            $stmt->bind_param("sssss", $id, $name, $password, $wins, $losses);
            if ($stmt->execute()){
                return true;
            }
            else {
                return false;
            }
            self::closeConnection();
        }

        static function updatePlayer($id, $password, $wins, $losses){
            $query = "UPDATE ".Credentials::$tablePlayers ." SET Wins = ?, Losses = ? WHERE Id = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("ssss", $wins, $losses, $id, $password);
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

        static function getSpecificField($playerId){
            $query = "SELECT * FROM ".Credentials::$tableFields." WHERE Id_Player = ? and Finished = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $finished = 0;
            $stmt->bind_param("ss", $playerId, $finished);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()){
                $visibleFieldString = $row['Field_visible'];
                $visibleField = explode(',', $visibleFieldString);
                $hiddenFieldString = $row['Field_hidden'];
                $hiddenField = explode(',', $hiddenFieldString);
                if ($row['Finished'] === 0){
                    $finished = false;
                }
                else {
                    $finished = true;
                }
                $field = new Field($row["Id_Player"], $row['Size'], $visibleField, $hiddenField, $finished);
            }
            $result->free_result();
            self::closeConnection();
            return $field;
        }

        static function createField($field){
            $query = "INSERT INTO ".Credentials::$tableFields." (Id_Player, Size, Field_visible, Field_hidden, Finished) VALUES (?, ?, ?, ?, ?)";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $id = $field->getPlayerId();
            $size = $field->getSize();
            $visibleField = $field->getVisibleField();
            $visibleFieldString = implode(',', $visibleField);
            $hiddenField = $field->getHiddenField();
            $hiddenFieldString = implode(',', $hiddenField);
            if ($field->getFinished()){
                $finished = 1;
            }
            else {
                $finished = 0;
            }
            $stmt->bind_param("sssss", $id, $size, $visibleFieldString, $hiddenFieldString, $finished);
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

        static function updateField($field, $id, $password){
            $query = "UPDATE fields JOIN players on fields.Id_Player = players.Id SET fields.Field_visible = ?, fields.Field_hidden = ?, fields.Finished = ? 
            WHERE fields.Id_Player = ? and players.Password = ? and fields.Finished = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $visibleField = $field->getVisibleField();
            $visibleFieldString = implode(',', $visibleField);
            $hiddenField = $field->getHiddenField();
            $hiddenFieldString = implode(',', $hiddenField);
            $finished = 0;
            if ($field->getFinished()){
                $finish = 1;
            }
            else {
                $finish = 0;
            }
            $stmt->bind_param("ssssss", $visibleFieldString, $hiddenFieldString, $finish, $id, $password, $finished);
            $stmt->execute();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
            self::closeConnection();
        }

        static function checkFinishedFields($playerID){
            $query = "SELECT * FROM ".Credentials::$tableFields." WHERE Id_Player = ? and Finished = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $finished = false;
            $stmt->bind_param("ss", $playerID, $finished);
            $stmt->execute();
            $stmt->store_result();
            $result = $stmt->num_rows;
            if ($result === 0){
                return false;
            }
            else {
                return true;
            }
            self::closeConnection();
        }

        static function deletePlayer($id, $password) {
            $query = "DELETE FROM ".Credentials::$tablePlayers." WHERE Id = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("ss", $id, $password);
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