<?php

// connection details for sql
$host = '127.0.0.1';
$db   = '4ww3';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

// settings/connection string
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
	PDO::ATTR_EMULATE_PREPARES   => false,
];

?>