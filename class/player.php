<?php
    class Player{
        public $name;
        public $password;
        public $wins;
        public $losses;

        public function __construct($name, $password, $wins, $losses){
            $this->name = $name;
            $this->password = $password;
            $this->wins = $wins;
            $this->losses = $losses;
        }

        public function __toString(){
            return 'Player {Name: '.$this->name.', Password: '.$this->password.', Wins: '.$this->wins.', Losses: '.$this->losses.'}';
        }

        public function getName(){
                return $this->name;
        }

        public function setName($name){
                $this->name = $name;

                return $this;
        }

        public function getPassword(){
                return $this->password;
        }

        public function setPassword($password){
                $this->password = $password;

                return $this;
        }

        public function getWins(){
                return $this->wins;
        }

        public function setWins($wins){
                $this->wins = $wins;

                return $this;
        }

        public function getLosses(){
                return $this->losses;
        }

        public function setLosses($losses){
                $this->losses = $losses;

                return $this;
        }
    }