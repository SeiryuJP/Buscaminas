<?php
    include_once 'class/field.php';
    include_once 'connection/connection.php';

    // header("Content-Type:application/json");
    $requestMethod = $_SERVER["REQUEST_METHOD"];
    $paths = $_SERVER['REQUEST_URI'];

    // switch ($requestMethod) {
    //     case 'GET':
    //         $args = explode('/', $paths);
    //         unset($args[0]);
    //         if (count($args) === 1){
    //             $content = file_get_contents('php://input');
    //             $data = json_decode($content, true);
    //             $user = $data['name'];
    //             if (empty($args[1])){
    //                 header("HTTP/1.1 202 Invalid request");
    //                 $message = [
    //                     'cod' => '202',
    //                     'desc' => 'Invalid number of arguments'
    //                 ];
    //             }
    //             else {
                    
    //             }
    //         }
    //         elseif (count($args) === 2) {
    //             if (empty($args[1]) || empty($args[2])){
    //                 header("HTTP/1.1 202 Invalid request");
    //                 $message = [
    //                     'cod' => '202',
    //                     'desc' => 'Invalid number of arguments'
    //                 ];
    //             }
    //             else {
    //                 $content = file_get_contents('php://input');
    //                 $data = json_decode($content, true);
    //                 $user = $data['name'];
    //                 $size = $args[1];
    //                 $mines = $args[2];

    //                 $field = new Field($user, $size);
    //                 $field->putMines($mines);

    //                 header("HTTP/1.1 200 Field created");
    //                 $message = [
    //                     'cod' => '200',
    //                     'field' => $field
    //                 ];
    //             }
    //         }
    //         else {
    //             header("HTTP/1.1 202 Invalid request");
    //             $message = [
    //                 'cod' => '202',
    //                 'desc' => 'Invalid number of arguments'
    //             ];
    //         }
    //         break;
        
    //     default:
    //         header("HTTP/1.1 405 Invalid request");
    //         $message = [
    //             'cod' => '405',
    //             'desc' => 'Invalid request'
    //         ];
    //         break;
    // }

    $field = new Field('prueba', 20);
    $field->putMines(3);

    echo $field;
    
    Connection::createField($field);

    // echo json_encode($message);