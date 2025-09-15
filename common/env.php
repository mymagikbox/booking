<?php
/**
 * Setup application environment
 */
$dotenv = \Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();


$dotenv->required([
    'APP_NAME',

    'DB_DSN',
    'POSTGRES_HOST',
    'POSTGRES_DB',
    'POSTGRES_PASSWORD',
    'POSTGRES_USER',
]);