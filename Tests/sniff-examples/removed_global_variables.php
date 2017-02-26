<?php

echo $HTTP_RAW_POST_DATA; // Bad.

echo $http_raw_post_data; // Ok - variable names are case-sensitive.
echo $HTTP_Raw_Post_Data; // Ok - variable names are case-sensitive.

// Bad.
$HTTP_POST_VARS;
$HTTP_GET_VARS;
$HTTP_ENV_VARS;
$HTTP_SERVER_VARS;
$HTTP_COOKIE_VARS;
$HTTP_SESSION_VARS;
$HTTP_POST_FILES;

// Issue #268
class TestClass {
    // OK.
    private $HTTP_POST_VARS;
    protected $HTTP_GET_VARS;
    public $HTTP_ENV_VARS;
    var $HTTP_SERVER_VARS;
    private $HTTP_COOKIE_VARS;
    protected $HTTP_SESSION_VARS;
    public $HTTP_POST_FILES;
    var $HTTP_RAW_POST_DATA;

    function testing() {
         // Bad.
        $HTTP_POST_VARS;
        $HTTP_GET_VARS;
        $HTTP_ENV_VARS;
        $HTTP_SERVER_VARS;
        $HTTP_COOKIE_VARS;
        $HTTP_SESSION_VARS;
        $HTTP_POST_FILES;
        echo $HTTP_RAW_POST_DATA;

        // Ok.
        self::$HTTP_POST_VARS;
        self::$HTTP_GET_VARS;
        static::$HTTP_ENV_VARS;
        static::$HTTP_SERVER_VARS;
        $this->HTTP_COOKIE_VARS;
        $this->HTTP_SESSION_VARS;
        $this->HTTP_POST_FILES;
        static::$HTTP_RAW_POST_DATA;

        // Bad.
        self::{$HTTP_GET_VARS};
        self::{$HTTP_ENV_VARS};
        self::{$HTTP_RAW_POST_DATA};
    }
}
