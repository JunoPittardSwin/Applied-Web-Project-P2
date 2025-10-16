<?php declare(strict_types=1); 

require_once(__DIR__ . '/lib/UserManager.php');
require_once(__DIR__ . '/lib/ReqUtils.php');
require_once(__DIR__ . '/settings.php');

session_start();

$userId = ReqUtils\Session::get('userId');

if ($userId === null)
{
	// User is unauthenticated, redirect to the login page.
	http_response_code(401);
	header('Location: /login.php');

	exit;
}

$userManager = new UserManager($db);
$user = $userManager->getUserById($userId);

if ($user === null)
{
	// No such user - the account must've been deleted during their session for whatever reason, so
	// we'll revoke the garbage session.
	session_unset();
	session_destroy();

	http_response_code(401);
	header('Location: /login.php');

	exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="./css/styles.css">
</head>
<body>
	<?php include(__DIR__ . '/header.inc'); ?>

	<main>
		Welcome to the administration dashboard, <?= htmlspecialchars($user->name) ?>!
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
