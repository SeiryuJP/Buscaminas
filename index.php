<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';

    header("Content-Type:application/json");

    
    Connection::updatePlayer('prueba', 2, 2);
    $user = Connection::getSpecificPlayer('prueba');

    echo json_encode($user);