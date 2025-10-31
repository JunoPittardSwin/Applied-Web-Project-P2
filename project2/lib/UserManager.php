<?php declare(strict_types=1);

/*
filename: UserManager.php
author: Ashlyn Randall
created: 17/10/2025
last modified: 27/10/2025
description: Database interaction for secure user authentication and privileges.
*/

require_once(__DIR__ . '/../settings.php');

/**
 * Shared functionality for user authentication. Users with accounts for the website can access the
 * administration dashboard (`/manage.php`), exposing management functionality without modifying
 * the server database directly.
 */
class UserManager
{
	/**
	 * Set up the database tables for users.
	 *
	 * @param mysqli $db
	 * @return void
	 */
	static function createSchema(mysqli $db)
	{
		// Ensure the users table exists!
		$db->query("CREATE TABLE IF NOT EXISTS users(
			id INTEGER PRIMARY KEY AUTO_INCREMENT,
			name varchar(32) NOT NULL UNIQUE,
			passwordHash TEXT NOT NULL
		)");
	}

	function __construct(private mysqli $db)
	{
		// Create the marker's account if it doesn't exist. (See Requirement 6)
		if ($this->getUserByName('Admin') === null)
		{
			$this->addUser(name: 'Admin', password: 'Admin');
		}
	}

	/**
	 * Attempt to log-in with the provided credentials.
	 * 
	 * @param string $name The name of the user to log in as.
	 * @param string $password The password of the user to log in as.
	 * @return ?User The user we authenticated as, if successful.
	 */
	function authenticate(string $name, #[\SensitiveParameter] string $password): ?User
	{
		$user = $this->getUserByName($name);

		if ($user?->authenticate($password) !== true)
		{
			return null;
		}

		return $user;
	}

	/**
	 * Get information about the given user by their ID, if they exist in the system.
	 *
	 * @param int $id
	 * @return ?User
	 */
	function getUserById(int $id): ?User
	{
		$result = $this->db->execute_query(
			"SELECT * FROM users
			 WHERE id = ?",
			[$id]
		);

		$row = $result->fetch_row();
		$result->close();

		if (!is_array($row))
		{
			return null;
		}

		return new User(...$row);
	}

	/**
	 * Get information about the given user by their name, if they exist in the system.
	 *
	 * @param string $name
	 * @return ?User
	 */
	function getUserByName(string $name): ?User
	{
		$result = $this->db->execute_query(
			"SELECT * FROM users
			 WHERE name = ?",
			[$name]
		);

		$row = $result->fetch_row();
		$result->close();

		if (!is_array($row))
		{
			return null;
		}

		return new User(...$row);
	}

	/**
	 * Create an account for a user with the provided credentials.
	 */
	function addUser(string $name, #[\SensitiveParameter] string $password)
	{
		$this->db->execute_query(
			"INSERT INTO users(name, passwordHash)
			 VALUES (?, ?)",
			[$name, password_hash($password, PASSWORD_BCRYPT)]
		);
	}
}

/**
 * An authenticated user who can administrate the website.
 */
readonly class User
{
	function __construct(
		/** Unique identifier. */
		public int $id,
		
		/** This user's name. */
		public string $name,

		/** The hash of the user's password. */
		#[\SensitiveParameter]
		private string $passwordHash
	)
	{}

	/**
	 * Attempt to authenticate as this user.
	 * 
	 * @param string $password The password to attempt to authenticate with
	 * @return bool Whether authentication was successful.
	 */
	public function authenticate(#[\SensitiveParameter] string $password): bool
	{
		return password_verify($password, $this->passwordHash);
	}
}
