<?php
    class Field{
        public $name;
        public $visibleField;
        public $size;
        public $hiddenField;

        public function __construct($name, $size){
            $this->name = $name;
            $this->visibleField = array_fill(0, $size, '');
            $this->size = $size;
            $this->hiddenField = array_fill(0, $size, '');
        }

        public function __toString(){
            return '{Size: '.$this->size.' Field: '.print_r($this->visibleField).'}';
        }
    
        public function putMines($numberMines){
            if ($numberMines > $this->size || $numberMines < 1){
                return false;
            }
            else {
                $count = 0;
                while ($count < $numberMines){
                    $count++;
                    $position = rand(0, count($this->visibleField)-1);
                    if ($this->visibleField[$position] === '*'){
                        while ($this->visibleField[$position] === '*'){
                            $position = rand(0, count($this->visibleField)-1);
                        }
                    }
                    $this->visibleField[$position] = '*';
                }
                $this->putNumbers();
                return true;
            } 
        }
    
        public function putNumbers(){
            for ($i = 0; $i < count($this->visibleField); $i++){
                if ($this->visibleField[$i] != '*'){
                    switch ($i) {
                        case 0:
                            if ($this->visibleField[$i+1] === '*'){
                                    $this->visibleField[$i] = 1;
                                }
                                else {
                                    $this->visibleField[$i] = 0;
                                }
                            break;
                        
                        case count($this->visibleField)-1:
                            if ($this->visibleField[$i-1] === '*'){
                                    $this->visibleField[$i] = 1;
                                }
                                else {
                                    $this->visibleField[$i] = 0;
                                }
                            break;
                        
                        default:
                            if ($this->visibleField[$i-1] === '*' && $this->visibleField[$i+1] === '*'){
                                $this->visibleField[$i];
                                $this->visibleField[$i] = 2;
                            }
                            elseif ($this->visibleField[$i-1] === '*' || $this->visibleField[$i+1] === '*'){
                                $this->visibleField[$i] = 1;
                            }
                            else{
                                $this->visibleField[$i] = 0;
                            }
                            break;
                    }
                }
            }
        }

        public function getName(){
                return $this->name;
        }

        public function setName($name){
                $this->name = $name;

                return $this;
        }

        public function getVisibleField(){
                return $this->visibleField;
        }

        public function setVisibleField($visibleField){
                $this->visibleField = $visibleField;

                return $this;
        }

        public function getSize(){
                return $this->size;
        }
 
        public function setSize($size){
                $this->size = $size;

                return $this;
        }

        public function getHiddenField(){
                return $this->hiddenField;
        }

        public function setHiddenField($hiddenField){
                $this->hiddenField = $hiddenField;

                return $this;
        }
    }