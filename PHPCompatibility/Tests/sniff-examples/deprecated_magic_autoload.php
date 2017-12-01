<?php

function __autoload() {
    echo 'I am the autoloader - I am deprecated from PHP 7.2 onwards';
}

class foo {
    function __autoload() {
        echo 'I am the autoloader in a class (which makes no sense) - I am deprecated from PHP 7.2 onwards';
    }
}
