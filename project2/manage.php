<?php declare(strict_types=1); 

require_once(__DIR__ . '/lib/UserManager.php');
require_once(__DIR__ . '/lib/Req.php');
require_once(__DIR__ . '/lib/Session.php');
require_once(__DIR__ . '/settings.php');

$userManager = new UserManager($db);
$user = Session\getUserOrLogin($userManager);

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
