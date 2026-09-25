<?php
// Database connection test
$host = getenv('DB_HOST') ?: 'mariadb';
$user = getenv('DB_USERNAME') ?: 'leonwp';
$pass = getenv('DB_PASSWORD') ?: 'leonwp';

try {
    $mysqli = new mysqli($host, $user, $pass);
    if ($mysqli->connect_error) {
        echo "Connection failed: " . $mysqli->connect_error;
    } else {
        echo "Database connection successful!";
        $mysqli->close();
    }
} catch (Throwable $exception) {
    echo "Connection failed: " . $exception->getMessage();
}