<?php

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

    function testing() {
		 // Bad.
        $HTTP_POST_VARS;
        $HTTP_GET_VARS;
        $HTTP_ENV_VARS;
        $HTTP_SERVER_VARS;
        $HTTP_COOKIE_VARS;
        $HTTP_SESSION_VARS;
        $HTTP_POST_FILES;

		// Ok.
        self::$HTTP_POST_VARS;
        self::$HTTP_GET_VARS;
        static::$HTTP_ENV_VARS;
        static::$HTTP_SERVER_VARS;
        $this->HTTP_COOKIE_VARS;
        $this->HTTP_SESSION_VARS;
        $this->HTTP_POST_FILES;

		// Bad.
        self::{$HTTP_GET_VARS};
        self::{$HTTP_ENV_VARS};
    }
}
