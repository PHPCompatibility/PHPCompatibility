<?php

dirname('somewhere', 5);

dirname( dirname( __FILE__ ) ); // Added after Github issue #111

dirname( plugin_basename( __FILE__ ) ); // Added after Github issue #111

dirname( plugin_basename( __FILE__ ), 2 ); // Added after Github issue #111

$data = unserialize($foo, ["allowed_classes" => false]);

session_start(array('bla'));

strstr('a', 'bla', true);
