<?php

require_once '../config/config.php';

$db = new PDO('mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8', Config::DB_USER, Config::DB_PASSWORD);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);