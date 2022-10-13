<?php
    class Field{
        public $field;
        public $size;

        public function __construct($size){
            $this->field = array_fill(0, $size, '');
            $this->size = $size;
        }

        public function __toString(){
            return '{Size: '.$this->size.' Field: '.print_r($this->field).'}';
        }
    
        public function putMines($numberMines){
            if ($numberMines > $this->size || $numberMines < 1){
                return false;
            }
            else {
                $count = 0;
                while ($count < $numberMines){
                    $count++;
                    $position = rand(0, count($this->field)-1);
                    if ($this->field[$position] === '*'){
                        while ($this->field[$position] === '*'){
                            $position = rand(0, count($this->field)-1);
                        }
                    }
                    $this->field[$position] = '*';
                }
                $this->putNumbers();
                return true;
            } 
        }
    
        public function putNumbers(){
            for ($i = 0; $i < count($this->field); $i++){
                if ($this->field[$i] != '*'){
                    switch ($i) {
                        case 0:
                            if ($this->field[$i+1] === '*'){
                                    $this->field[$i] = 1;
                                }
                                else {
                                    $this->field[$i] = 0;
                                }
                            break;
                        
                        case count($this->field)-1:
                            if ($this->field[$i-1] === '*'){
                                    $this->field[$i] = 1;
                                }
                                else {
                                    $this->field[$i] = 0;
                                }
                            break;
                        
                        default:
                            if ($this->field[$i-1] === '*' && $this->field[$i+1] === '*'){
                                $this->field[$i];
                                $this->field[$i] = 2;
                            }
                            elseif ($this->field[$i-1] === '*' || $this->field[$i+1] === '*'){
                                $this->field[$i] = 1;
                            }
                            else{
                                $this->field[$i] = 0;
                            }
                            break;
                    }
                }
            }
        }

        public function getField(){
                return $this->field;
        }

        public function setField($field){
                $this->field = $field;

                return $this;
        }

        public function getSize(){
                return $this->size;
        }
 
        public function setSize($size){
                $this->size = $size;

                return $this;
        }
    }