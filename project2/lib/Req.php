<?php declare(strict_types=1);

/**
 * Utilities for parsing data from requests.
 */
namespace Req;

/**
 * Get the query string parameter with the given name. If not given, returns `null`.
 *
 * @param string $name
 * @return string|null
 */
function get(string $name): ?string
{
	return $_GET[$name] ?? null;
}

/**
 * Get the POST-body request parameter with the given name. If not given, returns `null`.
 *
 * @param string $name
 * @return string|null
 */
function post(string $name): ?string
{
	return $_POST[$name] ?? null;
}

class Session
{
	/**
	 * Get session information with the given name. If that data is unset, returns `null`.
	 *
	 * @param string $name
	 * @return mixed
	 */
	static function get(string $name): mixed
	{
		return $_SESSION[$name] ?? null;
	}

	/**
	 * Set the session information for `$name` to `$data`.
	 *
	 * @param string $name The name of the entry in the session info
	 * @param mixed $data The data to store for that key
	 */
	static function set(string $name, mixed $data)
	{
		$_SESSION[$name] = $data;
	}
}
