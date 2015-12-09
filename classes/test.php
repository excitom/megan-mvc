<?php
require 'Db.php';
require 'DbConn.php';
require 'UsersDb.php';
$_SERVER = array();
$_SERVER['DB_HOST'] = 'megan2.c2kxqnmdhpzv.us-east-1.rds.amazonaws.com';
$_SERVER['DB_USER'] = 'admin';
$_SERVER['DB_PASSWORD'] = 'July5,1991';
$_SERVER['DB_DEFAULT'] = 'users';

$dbh = new UsersDb();
$res = $dbh->addUser('tom', 'password', 'tlang@halsoft.com', 'Tom', 'Lang');
var_dump($res); print "\n";
