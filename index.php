<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';

    header("Content-Type:application/json");
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $paths = $_SERVER['REQUEST_URI'];

    switch ($requestMethod) {
        case 'GET':
            $args = explode('/', $paths);
            unset($args[0]);
            $content = file_get_contents('php://input');
            $data = json_decode($content, true);
            $user = $data['name'];

            if (count($args) === 1){
                if (empty($args[1])){
                    header("HTTP/1.1 202 Invalid request");
                    $message = [
                        'cod' => '202',
                        'desc' => 'Invalid number of arguments'
                    ];
                }
                else {
                    $field = Connection::getSpecificField($user);
                    $visibleField = $field->getVisibleField();
                    $hiddenField = $field->getHiddenField();
                    $hiddenField[$args[1]-1] = $visibleField[$args[1]-1];

                    $field->setHiddenField($hiddenField);
                    Connection::updateField($field, $user);

                    if ($field->checkCondition($args[1]) === 'lose'){
                        header("HTTP/1.1 200 You lost");
                        $message = [
                            'cod' => '200',
                            'desc' => 'You lost',
                            'field' => $field
                        ];
                    }
                    elseif ($field->checkCondition($args[1]) === 'win'){
                        header("HTTP/1.1 200 You win");
                        $message = [
                            'cod' => '200',
                            'desc' => 'You win',
                            'field' => $field
                        ];
                    }
                    else{
                        header("HTTP/1.1 200 Still alive");
                        $message = [
                            'cod' => '200',
                            'desc' => 'Still alive',
                            'field' => $field
                        ];
                    }
                }
            }
            elseif (count($args) === 2) {
                if (empty($args[1]) || empty($args[2])){
                    header("HTTP/1.1 202 Invalid request");
                    $message = [
                        'cod' => '202',
                        'desc' => 'Invalid number of arguments'
                    ];
                }
                else {
                    $size = $args[1];
                    $mines = $args[2];

                    if ($data['existing-field']){
                        $field = new Field($user, $size, array_fill(0, $size, ''), array_fill(0, $size, ''));
                        $field->putMines($mines);
                        Connection::updateNewField($field, $user);

                        header("HTTP/1.1 200 Field created");
                        $message = [
                            'cod' => '200',
                            'desc' => 'Field created',
                            'field' => $field
                        ];
                    }
                    else {
                        $field = new Field($user, $size, array_fill(0, $size, ''), array_fill(0, $size, ''));
                        $field->putMines($mines);
                        Connection::createField($field);
    
                        header("HTTP/1.1 200 Field created");
                        $message = [
                            'cod' => '200',
                            'desc' => 'Field created',
                            'field' => $field
                        ];
                    }
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