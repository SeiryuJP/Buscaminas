<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';

    header("Content-Type:application/json");

    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $paths = $_SERVER['REQUEST_URI'];

    switch ($requestMethod){
        case 'GET':
            $content = file_get_contents('php://input');
            $data = json_decode($content, true);
            $loginResult = Connection::login($data['name'], $data['password']);
            if (!$loginResult){
                $message = [
                    $code = '202',
                    $desc = 'Invalid username or password'
                ];
            }
            else {
                $message = [
                    $code = '200',
                    $desc = $loginResult
                ];
            }
            break;
        case 'POST':
            $content = file_get_contents('php://input');
            $data = json_decode($content, true);
            $signUpResult = Connection::signUp($data['name'], $data['password']);
            if (!$signUpResult){
                $message = [
                    $code = '202',
                    $desc = 'Placeholder error'
                ];
            }
            else {
                $message = [
                    $code = '200',
                    $desc = 'User created correctly'
                ];
            }
    }

    // $size = 10;
    // $mines = 3;

    // $field = new Field($size);
    // $field->putMines($mines);

    // $players = Connection::getAllPlayers();
    // $result = Connection::login('admin', '1234');

    echo json_encode($message);