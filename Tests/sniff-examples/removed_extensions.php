<?php

activescript();
activescript_foobar();

cpdf();
Cpdf();
cpdf_foo();

dbase();

dbx();

dio();

fam();

fbsql();

fdf();

filepro();

hw_api();

ingres();

ircg();

mcve();

ming();

mnogosearch();

msql();

mysql_connect();

ncurses();

oracle();

ovrimos();

pfpro();

sqlite();

sybase();

w32api();

yp();

// examples of extension names that should not be considered wrong
mnogosearch;
function pfpro() {}
$ok = new Sybase();
$someObject->ming();

// More removed extensions:
mssql_bind();

ereg();

// @codingStandardsChangeSetting PHPCompatibility.PHP.RemovedExtensions functionWhitelist mysql_to_rfc3339
mysql_to_rfc3339();
