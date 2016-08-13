<?php

// This is ok.
dirname( dirname( __FILE__ ) );

// These should all be flagged.
dirname('somewhere', 5);
$data = unserialize($foo, ["allowed_classes" => false]);
session_start(array('bla'));
strstr('a', 'bla', true);
