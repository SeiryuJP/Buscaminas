<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';
    include_once 'phpmailer.php';
    include_once 'class/player.php';

    header("Content-Type:application/json");
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $paths = $_SERVER['REQUEST_URI'];

    switch ($requestMethod) {
        case 'GET':
            $args = explode('/', $paths);
            unset($args[0]);

            if ($args[1] === 'verification') {
                $mail = $_GET['email'];
                $player = Connection::getPlayerByMail($mail);
                $player->verifyMail();
                Connection::updateVerifiedStatus($mail);
                header("HTTP/1.1 200 email verified");
                $message = [
                    'cod' => '200',
                    'desc' => 'email verified'
                ];
            }
            else {
                $content = file_get_contents('php://input');
                $data = json_decode($content, true);
                $id = $data['id'];
                $user = $data['name'];
                $password = $data['password'];
    
                $playerData = Connection::getSpecificPlayer($id, $password);
                if (is_null($playerData)){
                    header("HTTP/1.1 401 User do not exist");
                    $message = [
                        'cod' => '401',
                        'desc' => 'User do not exist'
                    ];
                }
                else {
                    $wins = $playerData->getWins();
                    $losses = $playerData->getLosses();
                    $verified = $playerData->getVerified();
                    if (!$verified){
                        header("HTTP/1.1 401 User not verified");
                        $message = [
                            'cod' => '401',
                            'desc' => 'User not verified'
                        ];
                    }
                    else {
                        if (count($args) === 1){
                            if (empty($args[1])){
                                header("HTTP/1.1 202 Invalid request");
                                $message = [
                                    'cod' => '202',
                                    'desc' => 'Invalid number of arguments'
                                ];
                            }
                            elseif (Connection::checkFinishedFields($id)) {
                                $field = Connection::getSpecificField($id);
                                $field->uncover($args[1]-1);
            
                                if ($field->checkCondition($args[1]) === 'lose'){
                                    Connection::updateField($field, $id, $password);
                                    Connection::updatePlayer($id, $password, $wins, $losses + 1);
                                    header("HTTP/1.1 200 You lost");
                                    $message = [
                                        'cod' => '200',
                                        'desc' => 'You lost',
                                        'field' => $field
                                    ];
                                }
                                elseif ($field->checkCondition($args[1]) === 'win'){
                                    Connection::updateField($field, $id, $password);
                                    Connection::updatePlayer($id, $password, $wins + 1, $losses);
                                    header("HTTP/1.1 200 You win");
                                    $message = [
                                        'cod' => '200',
                                        'desc' => 'You win',
                                        'field' => $field
                                    ];
                                }
                                else{
                                    if (!Connection::updateField($field, $id, $password)){
                                        header("HTTP/1.1 202 Invalid Password");
                                        $message = [
                                            'cod' => '202',
                                            'desc' => 'Invalid Password',
                                        ];
                                    }
                                    header("HTTP/1.1 200 Still alive");
                                    $message = [
                                        'cod' => '200',
                                        'desc' => 'Still alive',
                                        'field' => $field
                                    ];
                                }
                            }
                            else {
                                header("HTTP/1.1 202 No active fields");
                                    $message = [
                                        'cod' => '202',
                                        'desc' => 'No active fields'
                                    ];
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
                            elseif (!Connection::checkFinishedFields($id)) {
                                $size = $args[1];
                                $mines = $args[2];
            
                                $field = new Field($id, $size, array_fill(0, $size, ''), array_fill(0, $size, ''));
                                $field->putMines($mines);
                                Connection::createField($field);
            
                                header("HTTP/1.1 200 Field created");
                                $message = [
                                    'cod' => '200',
                                    'desc' => 'Field created',
                                    'field' => $field
                                ];
                            }
                            else {
                                header("HTTP/1.1 202 There is an Active field");
                                $message = [
                                    'cod' => '202',
                                    'desc' => 'There is an Active field'
                                ];
                            }
                        }
                    }
                }
                }
            break;
        
        case 'POST':
            $content = file_get_contents('php://input');
            $data = json_decode($content, true);
            $id = $data['id'];
            $user = $data['name'];
            $password = $data['password'];
            $mail = $data['mail'];

            if (Connection::signUp($id, $user, $password, $mail)){
                Mailer::sendVerificationMail($mail, $user);
                header("HTTP/1.1 200 User created");
                $message = [
                    'cod' => '200',
                    'desc' => 'User created, verify your email'
                ];
            }
            else {
                header("HTTP/1.1 202 User cannot be created");
                $message = [
                    'cod' => '202',
                    'desc' => 'User cannot be created'
                ];
            }
            break;

        case 'PUT':
            $args = explode('/', $paths);
            unset($args[0]);

            $content = file_get_contents('php://input');
            $data = json_decode($content, true);

            
            
            break;

        case 'DELETE':
            $content = file_get_contents('php://input');
            $data = json_decode($content, true);
            $id = $data['id'];
            $password = $data['password'];

            if (Connection::deletePlayer($id, $password)){
                header("HTTP/1.1 200 Player deleted");
                $message = [
                    'cod' => '200',
                    'desc' => 'Player deleted'
                ];
            }
            else {
                header("HTTP/1.1 202 Cannot delete player");
                $message = [
                    'cod' => '202',
                    'desc' => 'Cannot delete player'
                ];
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