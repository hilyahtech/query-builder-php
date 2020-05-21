<?php

require_once '../src/DB.php';

$db = new \HilyahTech\QueryBuilder\DB([
    'driver' => 'mysql',
    'host' => 'localhost',
    'database' => 'test',
    'username' => 'root',
    'password' => ''
]);

$kelas = $db->table('kelas')->get();

var_dump($kelas);