<?php

A:: {$obj->getConstantName('param')}; // valid syntax only since PHP 8.3
self::{$name}; // valid syntax only since PHP 8.3
static::{$array['key']}; // valid syntax only since PHP 8.3
static::{B::class}; // make sure double colon `::` on the inner expression doesn't confuse the sniff
