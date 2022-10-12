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
                    $player = new Player($row["Id"], $row["Name"], $row["Password"], $row["Wins"], $row["Losses"]);
                    $playersArray[] = $player;
                }
            }
            self::$result->free_result();
            self::closeConnection();
            return $playersArray;
        }

        static function getSpecificPlayer($id){
            self::$query = "SELECT * FROM ".Credentials::$tablePlayers." WHERE Id = ?";
            self::startConnection();
            $stmt = self::$connection->prepare(self::$query);
            $stmt->bind_param("s", $id);
            $stmt->execute();
            self::$result = $stmt->get_result();
            while ($row = self::$result->fetch_assoc()){
                $player = new Player($row["Id"], $row["Name"], $row["Password"], $row["Wins"], $row["Losses"]);
            }
            self::$result->free_result();
            self::closeConnection();
            return $player;
        }

        // static function addPersona($id, $name, $phone){
        //     self::$query = "INSERT INTO ".Credentials::$tablePersona." VALUES (?, ?, ?, ?)";
        //     self::startConnection();
        //     $stmt = self::$connection->prepare(self::$query);
        //     $passwd = "";
        //     $stmt->bind_param("ssss", $id, $name, $passwd, $phone);
        //     if ($stmt->execute()){
        //         self::$result = true;
        //     }
        //     else {
        //         self::$result = false;
        //     }
        //     self::closeConnection();
        //     return self::$result;
        // }

        // static function editPersona($id, $name, $phone){
        //     self::$query = "UPDATE ".Credentials::$tablePersona ." SET Nombre = ?, Clave = ?, Tfno = ? WHERE DNI = ?";
        //     self::startConnection();
        //     $stmt = self::$connection->prepare(self::$query);
        //     $passwd = "";
        //     $stmt->bind_param("ssss", $name, $passwd, $phone, $id);
        //     $stmt->execute();
        //     if ($stmt->affected_rows){
        //         $edited = true;
        //     }
        //     else {
        //         $edited = false;
        //     }
        //     self::closeConnection();
        //     return $edited;
        // }

        // static function deletePersona($id){
        //     self::$query = "DELETE FROM ".Credentials::$tablePersona." WHERE DNI = ?";
        //     self::startConnection();
        //     $stmt = self::$connection->prepare(self::$query);
        //     $stmt->bind_param("s", $id);
        //     $stmt->execute();
        //     if ($stmt->affected_rows){
        //         $deleted = true;
        //     }
        //     else {
        //         $deleted = false;
        //     }
        //     self::closeConnection();
        //     return $deleted;
        // }
    }
?>