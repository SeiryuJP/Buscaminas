<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';

    header("Content-Type:application/json");

    $size = 10;
    $mines = 3;

    $field = new Field($size);
    $field->putMines($mines);

    $players = Connection::getAllPlayers();

    echo json_encode($players);