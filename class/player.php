<?php
    class Player{
        public $id;
        public $name;
        public $wins;
        public $losses;
        public $verified;
        public $mail;

        public function __construct($id, $name, $wins, $losses, $verified, $mail){
            $this->id = $id;
            $this->name = $name;
            $this->wins = $wins;
            $this->losses = $losses;
            $this->verified = $verified;
            $this->mail = $mail;
        }

        public function __toString(){
            return 'Player {Name: '.$this->name.', Password: '.$this->password.', Wins: '.$this->wins.', Losses: '.$this->losses.'}';
        }

        public function verifyMail(){
            $this->verified = true;
        }

        public function getName(){
                return $this->name;
        }

        public function setName($name){
                $this->name = $name;

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

        public function getId(){
                return $this->id;
        }

        public function setId($id){
                $this->id = $id;

                return $this;
        }

        public function getVerified(){
                return $this->verified;
        }

        public function setVerified($verified){
                $this->verified = $verified;

                return $this;
        }

        public function getMail(){
                return $this->mail;
        }

        public function setMail($mail){
                $this->mail = $mail;

                return $this;
        }
    }