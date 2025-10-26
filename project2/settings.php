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

/**
 * @var mysqli Connection to our MySQL database.
 */
$db = new mysqli($DB_HOST, $DB_USER, $DB_PASSWORD);

// Ensure that our database exists before anything else.
$db->execute_query("CREATE DATABASE IF NOT EXISTS $DB_NAME");
$db->select_db($DB_NAME);

// below: creates tables if they do not already exist
// creates the team_members table
$db->execute_query("CREATE TABLE IF NOT EXISTS team_members (
	student_id INT(9) NOT NULL PRIMARY KEY,
	name VARCHAR(50) NOT NULL,
	quote TEXT NOT NULL,
	language VARCHAR(25) NOT NULL,
	translation TEXT NOT NULL,
	job VARCHAR(75) NOT NULL,
	snack VARCHAR(50) NOT NULL,
	town VARCHAR(50) NOT NULL,
	study VARCHAR(50) NOT NULL,
	element VARCHAR(20) NOT NULL
);");

// creates the contributions sub-table
$db->execute_query("CREATE TABLE IF NOT EXISTS contributions (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	team_member_id INT NOT NULL,
	contribution_text TEXT NOT NULL,
	FOREIGN KEY (team_member_id) REFERENCES team_members(student_id)
	);");
	