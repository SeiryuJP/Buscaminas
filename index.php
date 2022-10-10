<?php
    function generateField($size){
        $field = array_fill(0, $size, '');
        return $field;
    }

    function putMines(&$field, $numberMines){
        $count = 0;
        while ($count < $numberMines){
            $count++;
            $position = rand(0, count($field)-1);
            $field[$position] = '*';
        }
    }

    function putNumbers(&$field){
        for ($i = 0; $i < count($field); $i++){
            if ($field[$i] != '*'){
                switch ($i) {
                    case 0:
                        if ($field[$i+1] === '*'){
                                $field[$i] = 1;
                            }
                            else {
                                $field[$i] = 0;
                            }
                        break;
                    
                    case count($field)-1:
                        if ($field[$i-1] === '*'){
                                $field[$i] = 1;
                            }
                            else {
                                $field[$i] = 0;
                            }
                        break;
                    
                    default:
                        if ($field[$i-1] === '*' && $field[$i+1] === '*'){
                            $field[$i];
                            $field[$i] = 2;
                        }
                        elseif ($field[$i-1] === '*' || $field[$i+1] === '*'){
                            $field[$i] = 1;
                        }
                        else{
                            $field[$i] = 0;
                        }
                        break;
                }
            }
        }
    }

    $size = 10;
    $mines = 4;

    $field = generateField($size);
    putMines($field, $mines);
    putNumbers($field);

    // echo count($field);
    print_r($field);