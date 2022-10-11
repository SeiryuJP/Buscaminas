<?php
    require_once 'class/field.php';
    header("Content-Type:application/json");

    $size = 10;
    $mines = 3;

    $field = new Field($size);
    $field->putMines($mines);

    echo json_encode($field);