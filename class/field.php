<?php
    class Field{
        public $name;
        public $visibleField;
        public $size;
        public $hiddenField;
        public $finished;

        public function __construct($name, $size, $visibleField, $hiddenField){
            $this->name = $name;
            $this->visibleField = $visibleField;
            $this->size = $size;
            $this->hiddenField = $hiddenField;
            $this->finished = false;
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

        public function checkCondition($position){
            $count = 0;
            $mines = 0;

            for ($i = 0; $i < count($this->visibleField); $i++){
                if ($this->visibleField[$i] === '*'){
                    $mines = $mines + 1;
                }
            } 
            
            for ($i = 0; $i < count($this->hiddenField); $i++){
                if ($this->hiddenField[$i] != '' && $this->hiddenField[$i] != '*'){
                    $count = $count + 1;
                }
            }

            if ($this->visibleField[$position-1] === '*'){
                $this->finished = true;
                return 'lose';
            }
            elseif ($count === count($this->hiddenField)-$mines){
                $this->finished = true;
                return 'win';
            }
            else {
                return 'alive';
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

        public function getFinished(){
            return $this->finished;
        }

        public function setFinished($finished){
                $this->finished = $finished;

                return $this;
        }
    }