<?php declare(strict_types=1);

/**
 * Database Credentials.
 * 
 * On deployment or generally when using Docker, credentials are fetched from environment variables,
 * provided by Docker.
 * 
 * When using XAMPP, the credentials will instead be the ones listed here as fallbacks.
 * 
 * Example usage:
 * 
 * ```
 * require_once(__DIR__ . '/settings.php');
 * $db = new PDO("mysql:host=$DB_HOST;port=3306;dbname=project2", $DB_USER, $DB_PASSWORD);
 * ```
 */

$DB_HOST = getenv('MYSQL_HOST') ?: '127.0.0.1';
$DB_USER = getenv('MYSQL_USER') ?: 'root';

/**
 * For the assignment, we are expected to set **no password** for the database. For flexibility, if
 * we were to "deploy" this website, a password may be provided via a path in the filesystem - thus
 * making it compatible with Docker Secrets.
 * 
 * However, if this variable is unset, a blank password is used. On XAMPP, this will be the default
 * behaviour.
 */
$DB_PASSWORD = getenv('MYSQL_PASSWORD_FILE') ? file_get_contents(getenv('MYSQL_PASSWORD_FILE')) : '';

/**
 * Name of the database in MySQL.
 */
$DB_NAME = 'part2_db';

// Ensure that our database exists before anything else.
$temporaryMysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD);
$temporaryMysqli->execute_query("CREATE DATABASE IF NOT EXISTS $DB_NAME");
$temporaryMysqli->close();
unset($temporaryMysqli);

// declares the shared database
$db = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD, $DB_NAME);

