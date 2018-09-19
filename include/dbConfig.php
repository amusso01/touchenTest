<?php

/**
 * Internal credential to our database (the TT database in our case)
 */

$driver = 'mysql';
$dbName = 'tt';
$host = 'localhost';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "$driver:host=$host;dbname=$dbName;charset=$charset";
$options = [
	PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
	PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];


/**
 * Connection to our DB, instance name: $pdoTT
 */

try {
	$pdoTT = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
	throw new PDOException($e->getMessage(), (int)$e->getCode());
}


/**
 * Credential for the unknown source DB
 * If need uncomment and fill the variables with value different from Internal DB
 */

//$driver = '';
$dbName = 'source';
//$host = '';
//$user = '';
//$pass = '';
//$charset = '';

$dsn = "$driver:host=$host;dbname=$dbName;charset=$charset";


/**
 * Connection to external source DB , instance name: $pdoSource
 */

try {
	$pdoSource = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $i) {
	throw new PDOException($i->getMessage(), (int)$i->getCode());
}