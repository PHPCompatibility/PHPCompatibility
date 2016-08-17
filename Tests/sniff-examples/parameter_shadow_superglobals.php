<?php

// These should all be flagged.
function testingA( $GLOBALS ) {}
function testingB( $_SERVER ) {}
function testingC( $_GET ) {}
function testingD( $_POST ) {}
function testingE( $_FILES ) {}
function testingF( $_COOKIE ) {}
function testingG( $_SESSION ) {}
function testingH( $_REQUEST ) {}
function testingI( $_ENV ) {}

// Test case-insensitive compare.
function testingJ( $globals ) {}
function testingK( $_post ) {}

// This should be ok.
function testingL( $POST ) {}
