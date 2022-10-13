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

                $_SESSION['numberMines'] = 3;
                $_SESSION['successCount'] = 0;
                $_SESSION['loseStatus'] = false;
                $_SESSION['winStatus'] = false;
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

                $_SESSION['numberMines'] = $args[2];
                $_SESSION['successCount'] = 0;
                $_SESSION['loseStatus'] = false;
                $_SESSION['winStatus'] = false;
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
                if (!$_SESSION['loseStatus'] && !$_SESSION['winStatus']){
                    $position = $args[1];
                    $_SESSION['hiddenField'][$position-1] = $field[$position-1];
                    if ($_SESSION['hiddenField'][$position-1] === '*'){
                        $_SESSION['loseStatus'] = true;
                        
                        header("HTTP/1.1 200 You lose");
                        $message = [
                            'cod' => '200',
                            'field' => $_SESSION['hiddenField']
                        ];
                    }
                    else {
                        $_SESSION['successCount'] = $_SESSION['successCount'] + 1;
                        if ($_SESSION['successCount'] === count($field) - $_SESSION['numberMines']){
                            $_SESSION['winStatus'] = true;
                        }

                        header("HTTP/1.1 200 Mine avoided");
                        $message = [
                            'cod' => '200',
                            'field' => $_SESSION['hiddenField']
                        ];
                    }
                }
                else {
                    header("HTTP/1.1 200 New field needed");
                    $message = [
                        'cod' => '202',
                        'desc' => 'create a new field'
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