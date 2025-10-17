<?php declare(strict_types=1);

/**
 * Session-tracking for user login, used by the management interface.
 */
namespace Session;

require_once(__DIR__ . '/UserManager.php');
require_once(__DIR__ . '/ReqUtils.php');

session_start();

/**
 * Authenticate the current session and return the user's information if successful. If
 * unsuccessful, redirects the user to the login page.
 *
 * @param \UserManager $userManager
 * @return \User
 */
function getUserOrLogin(\UserManager $userManager): \User
{
	$userId = \ReqUtils\Session::get('userId');

	if ($userId === null)
	{
		// User is unauthenticated, redirect to the login page.
		http_response_code(401);
		header('Location: /login.php');

		exit;
	}

	$user = $userManager->getUserById($userId);

	if ($user === null)
	{
		// No such user - the account must've been deleted during their session for whatever reason,
		// so we'll revoke the garbage session.
		setUser(null);

		http_response_code(401);
		header('Location: /login.php');

		exit;
	}

	return $user;
}

/**
 * Set the current logged-in user for this client's session.
 * 
 * @param \User|null $user
 */
function setUser(?\User $user)
{
	if ($user === null)
	{
		session_unset();
		session_destroy();

		return;
	}
	
	\ReqUtils\Session::set('userId', $user->id);
}
