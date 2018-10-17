<?php

class test {

    public $abc;

    public function __construct() {
        $this->demo();
    }

    public function demo() {
        echo 'this is class';
    }

}

$abc = new test();
?>