<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';

    session_start();

    header("Content-Type:application/json");
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $paths = $_SERVER['REQUEST_URI'];

    switch ($requestMethod) {
        case 'GET':
            $args = explode('/', $paths);
            unset($args[0]);
            if (empty($args[1]) && empty($args[2])){
                $_SESSION['fieldClass'] = new Field(10);
                $_SESSION['fieldClass']->putMines(3);
                $_SESSION['hiddenField'] = array_fill(0, count($_SESSION['fieldClass']->getField()), '-');

                header("HTTP/1.1 200 Field created");
                $message = [
                    'cod' => '200',
                    'field' => $_SESSION['hiddenField']
                ];
            }
            elseif (empty($args[2])){
                header("HTTP/1.1 202 Empty mines");
                $message = [
                    'cod' => '202',
                    'desc' => 'Empty mines'
                ];
            }
            else{
                $_SESSION['fieldClass'] = new Field($args[1]);
                $_SESSION['fieldClass']->putMines($args[2]);
                $_SESSION['hiddenField'] = array_fill(0, count($_SESSION['fieldClass']->getField()), '-');

                header("HTTP/1.1 200 Field created");
                $message = [
                    'cod' => '200',
                    'field' => $_SESSION['hiddenField']
                ];
            }
            break;

        case 'POST':
            $field = $_SESSION['fieldClass']->getField();
            $args = explode('/', $paths);
            unset($args[0]);
            if (empty($args[1]) || $args[1] > count($field)){
                header("HTTP/1.1 202 Invalid postion");
                $message = [
                    'cod' => '202',
                    'desc' => 'invalid position',
                    'field' => $_SESSION['hiddenField']
                ];
            }
            else {
                $position = $args[1];
                $_SESSION['hiddenField'][$position-1] = $field[$position-1];
                if ($_SESSION['hiddenField'][$position-1] === '*'){
                    header("HTTP/1.1 200 You lose");
                    $message = [
                        'cod' => '200',
                        'field' => $_SESSION['hiddenField']
                    ];
                }
                else {
                    header("HTTP/1.1 200 Mine avoided");
                    $message = [
                        'cod' => '200',
                        'field' => $_SESSION['hiddenField']
                    ];
                }
            }
            break;
            
        default:
            header("HTTP/1.1 405 Invalid request");
            $message = [
                'cod' => '405',
                'desc' => 'Invalid request'
            ];
            break;
    }

    echo json_encode($message);