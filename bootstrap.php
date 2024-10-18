<?php

require 'vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

$capsule->addConnection([
    'driver' => 'mysql',
    'host' => 'mariadb',
    'database' => 'customer_db',
    'username' => 'user',
    'password' => 'password',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
//    'port' => '3307',
//    'strict' => true,
//    'engine' => null,
]);

// Set the global instance
$capsule->setAsGlobal();

// Boot Eloquent
$capsule->bootEloquent();

// to check the DB connection
//try {
//    Capsule::connection()->getPdo();
//    echo "Connected successfully to the database.";
//} catch (Exception $e) {
//    die("Could not connect to the database. Error: " . $e->getMessage());
//}
