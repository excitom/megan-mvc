<?php
/*
 * Find the directory containing the framework, which is by
 * convention one directory up.
 */
$p = explode('/', $_SERVER['DOCUMENT_ROOT']);
array_pop($p);
$_SERVER['FW_ROOT'] = join('/', $p);
require_once $_SERVER['FW_ROOT'].'/fw.php';
