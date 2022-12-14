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
                    $player = new Player($row["Name"], $row["Password"], $row["Wins"], $row["Losses"], $row['Verified'], $row['mail']);
                    $playersArray[] = $player;
                }
            }
            $result->free_result();
            self::closeConnection();
            return $playersArray;
        }

        static function getSpecificPlayer($id, $password){
            $player = null;
            $query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Id = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("ss", $id, $password);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()){
                if ($row['Verified'] === 0){
                    $verified = false;
                }
                else {
                    $verified = true;
                }
                $player = new Player($row["Id"], $row['Name'], $row["Wins"], $row["Losses"], $verified, $row['Mail']);
            }
            $result->free_result();
            self::closeConnection();
            return $player;
        }

        static function getPlayerByMail($mail){
            $query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Mail = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("s", $mail);
            $stmt->execute();
            $result = $stmt->get_result();
            while ($row = $result->fetch_assoc()){
                if ($row['Verified'] === 0){
                    $verified = false;
                }
                else {
                    $verified = true;
                }
                $player = new Player($row["Id"], $row['Name'], $row["Wins"], $row["Losses"], $verified, $row['Mail']);
            }
            $result->free_result();
            self::closeConnection();
            return $player;
        }

        static function signUp($id, $name, $password, $mail){
            $query = "INSERT INTO ".Credentials::$tablePlayers." (Id, Name, Password, Wins, Losses, Verified, Mail) VALUES (?, ?, ?, ?, ?, ?, ?)";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $wins = 0;
            $losses = 0;
            $verified = 0;
            $stmt->bind_param("sssssss", $id, $name, $password, $wins, $losses, $verified, $mail);
            if ($stmt->execute()){
                self::closeConnection();
                return true;
            }
            else {
                self::closeConnection();
                return false;
            }
        }

        static function updatePlayer($id, $password, $wins, $losses){
            $query = "UPDATE ".Credentials::$tablePlayers ." SET Wins = ?, Losses = ? WHERE Id = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("ssss", $wins, $losses, $id, $password);
            $stmt->execute();
            self::closeConnection();
            if ($stmt->affected_rows){
                $edited = true;
            }
            else {
                $edited = false;
            }
            return $edited;
        }

        static function updateVerifiedStatus($mail){
            $query = "UPDATE ".Credentials::$tablePlayers ." SET Verified = ? WHERE Mail = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $verified = 1;
            $stmt->bind_param("ss", $verified, $mail);
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
            self::closeConnection();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
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
            self::closeConnection();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
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
            self::closeConnection();
            if ($result === 0){
                return false;
            }
            else {
                return true;
            }
        }

        static function deletePlayer($id, $password) {
            $query = "DELETE FROM ".Credentials::$tablePlayers." WHERE Id = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("ss", $id, $password);
            $stmt->execute();
            self::closeConnection();
            if ($stmt->affected_rows){
                return true;
            }
            else {
                return false;
            }
        }

        static function updatePassword($id, $password, $newPassword){
            $query = "UPDATE ".Credentials::$tablePlayers ." SET Password = ? WHERE Id = ? and Password = ?";
            self::startConnection();
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param("sss", $newPassword, $id, $password);
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