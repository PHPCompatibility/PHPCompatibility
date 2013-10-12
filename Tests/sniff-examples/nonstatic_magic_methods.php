<?php

class Plain
{
    function __get($name) {}
    function __set($name, $value) {}
    function __isset($name) {}
    function __unset($name) {}
    function __call($name, $arguments) {}
}

class Normal
{
    public function getId() {}
    public function __get($name) {}
    public function __set($name, $value) {}
    public function __isset($name) {}
    public function __unset($name) {}
    public function __call($name, $arguments) {}
}

class TooPrivate
{
    private function __get($name) {}
    private function __set($name, $value) {}
    private function __isset($name) {}
    private function __unset($name) {}
    private function __call($name, $arguments) {}
}

class TooProtected
{
    protected function __get($name) {}
    protected function __set($name, $value) {}
    protected function __isset($name) {}
    protected function __unset($name) {}
    protected function __call($name, $arguments) {}
}

class TooStatic
{
    static function __get($name) {}
    static function __set($name, $value) {}
    static function __isset($name) {}
    static function __unset($name) {}
    static function __call($name, $arguments) {}
}

class TooStaticPublic
{
    static public function __get($name) {}
}

class TooPublicStatic
{
    public static function __get($name) {}
}

class TooStaticPrivate
{
    static private function __get($name) {}
}

class TooPrivateStatic
{
    private static function __get($name) {}
}

class TooStaticProtected
{
    static protected function __get($name) {}
}

class TooProtectedStatic
{
    protected static function __get($name) {}
}

class StackedStaticPrivate
{
    static
    private
    function
    __get($name) {}
}

interface PlainInterface
{
    function __get($name);
    function __set($name, $value);
    function __isset($name);
    function __unset($name);
    function __call($name, $arguments);
}

interface NormalInterface
{
    public function getId();
    public function __get($name);
    public function __set($name, $value);
    public function __isset($name);
    public function __unset($name);
    public function __call($name, $arguments);
}

interface TooPrivateInterface
{
    private function getId();
    private function __get($name);
}
