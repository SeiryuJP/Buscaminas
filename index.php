<?php
    require_once 'class/field.php';
    header("Content-Type:application/json");

    $size = 10;
    $mines = 4;

    $field = new Field($size);
    $field->putMines($mines);
    $field->putNumbers();

    echo json_encode($field);