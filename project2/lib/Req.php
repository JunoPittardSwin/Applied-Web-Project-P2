<?php declare(strict_types=1);

/**
 * Utilities for parsing data from requests.
 */
namespace Req;

/**
 * Get the query string parameter with the given name.
 * If not given or blank, returns `null`.
 *
 * @param string $name
 * @return string|null
 */
function get(string $name): ?string
{
	$value = $_GET[$name] ?? '';
	$value = mb_trim($value = $value);

	if ($value === '')
	{
		return null;
	}

	return $value;
}

/**
 * Get the POST-body request parameter with the given name.
 * If not given or blank, returns `null`.
 *
 * @param string $name
 * @return string|null
 */
function post(string $name): ?string
{
	$value = $_POST[$name] ?? '';
	$value = mb_trim($value = $value);

	if ($value === '')
	{
		return null;
	}

	return $value;
}

/**
 * Get the POST-body request parameter with the given name, or bail out with a HTTP 400 error.
 *
 * @param string $name
 * @return string
 */
function postOrBail(string $name): string
{
	$value = post($name);

	if ($value === null)
	{
		http_response_code(400);
		exit;
	}

	return $value;
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
