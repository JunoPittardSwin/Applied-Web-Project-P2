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

// lines 46-96: table creation
//creates the EOI table
$db->execute_query("CREATE TABLE IF NOT EXISTS eoi (
  id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
  ref VARCHAR(5) NOT NULL,
  fname VARCHAR(40) NOT NULL,
  lname VARCHAR(40) NOT NULL,
  dob VARCHAR(10) NOT NULL,
  gender VARCHAR(15) NOT NULL,
  street VARCHAR(40) NOT NULL,
  suburb VARCHAR(40) NOT NULL,
  state VARCHAR(3) NOT NULL,
  postcode INT(4) NOT NULL,
  email VARCHAR(50) NOT NULL,
  phone INT(12) NOT NULL,
  skills SET('soc_siem','incident_response','vuln_mgmt','cloud_security','iam_mfa','network_security','scripting','other') NOT NULL,
  other_skills text NOT NULL,
  status ENUM('New','Current','Final') NOT NULL DEFAULT 'New'
);");

// creates the jobs table
$db->execute_query("CREATE TABLE IF NOT EXISTS jobs (
	ref VARCHAR(5) NOT NULL PRIMARY KEY,
	title VARCHAR(50) NOT NULL,
	salary_low INT NOT NULL,
	salary_high INT NOT NULL,
	reporting_line VARCHAR(50) NOT NULL,
	about TEXT NOT NULL
	);");

// creates the jobs "essential requirements" sub-table
$db->execute_query("CREATE TABLE IF NOT EXISTS jobs_ess_reqs (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref VARCHAR(5) NOT NULL,
	ess_text TEXT NOT NULL,
	FOREIGN KEY (ref) REFERENCES jobs(ref)
	);");

// creates the jobs "preferred requirements" sub-table
$db->execute_query("CREATE TABLE IF NOT EXISTS jobs_pref_reqs (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	ref VARCHAR(5) NOT NULL,
	pref_text TEXT NOT NULL,
	FOREIGN KEY (ref) REFERENCES jobs(ref)
	);");

// creates the contributions table
$db->execute_query("CREATE TABLE IF NOT EXISTS contributions (
	id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	team_member ENUM('Ashlyn','Juno','Aadil') NOT NULL,
	contribution_text TEXT NOT NULL
	);");

//lines 99- : default data insertion

//checks if jobs table is empty
$jobs_check = $db->execute_query("SELECT IF(NOT EXISTS(SELECT * FROM jobs), 'TRUE', 'FALSE'); ");
$jobs_empty = mysqli_fetch_row($jobs_check)[0];

// inserts default jobs table data, insert-where code referenced from stackoverflow user @RichardTheKiwi
$db->execute_query("INSERT INTO jobs
SELECT 'J0115', 'Security Analyst', '80000', '100000', 'Analyst Team Lead',
'As a security analyst, you will work with our IT team to analyse and respond to emerging and existing threats. You will be tasked with monitoring our existing clients'' projects and ensuring security. We ask you to keep up with modern and emerging attack vectors and potential vulnerabilities to pre-empt attacks.'
WHERE ($jobs_empty = TRUE);");

$db->execute_query("INSERT INTO jobs 
SELECT 'J0201', 'Incident Response Specialist', '120000', '160000', 'Incident Team Coordinator',
'As an Incident Response Specialist, you will join our threat strike team and work on mitigating time-critical active threats. You will work with our analysts and clients to respond to cyberattacks as they happen, and assist in recovery afterwards. When there are no current incidents, you will be expected to proactively seek out new or advanced threats, which may evade regular detection.'
WHERE ($jobs_empty = TRUE);");

$db->execute_query("INSERT INTO jobs 
SELECT 'J0302', 'Secure Culture Coordinator', '100000', '120000', 'Chief Communications Officer',
'As our Secure Culture Coordinator, you will work with our clients to ensure that their organisation has good individual-level cybersecurity practices. You will be responsible for coordinating outreach, and ensuring that every employee at the clients'' business is aware of common attack vectors like phishing. '
WHERE ($jobs_empty = TRUE);");