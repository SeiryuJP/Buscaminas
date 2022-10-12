<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';

    header("Content-Type:application/json");
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $paths = $_SERVER['REQUEST_URI'];

    $size = 10;
    $mines = 3;

    $field = new Field($size);
    $field->putMines($mines);

    $players = Connection::getAllPlayers();
    $result = Connection::login('admin', '1234');

    echo json_encode($result);