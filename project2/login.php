<?php declare(strict_types=1);

use function Templates\document;

require_once(__DIR__ . '/lib/UserManager.php');
require_once(__DIR__ . '/lib/Req.php');
require_once(__DIR__ . '/lib/Session.php');
require_once(__DIR__ . '/lib/templates/document.php');
require_once(__DIR__ . '/settings.php');

$userManager = new UserManager($db);

switch ($_SERVER['REQUEST_METHOD'])
{
	case 'GET':
		echo document(
			title: 'Admin Login',
			description: 'Login for the administration interface.',
			mainContent: function()
			{
				ob_start(); ?>
				<form action="" method="post">
					<h1>Site Management Log-in</h1>
					<p>
						Not a site manager?
						<a href="./index.php">Take me back home.</a>
					</p>

					<p>
						<label for="name">Name</label>
						<input type="text" name="name" id="name" required>
					</p>

					<p>
						<label for="password">Password</label>
						<input type="password" name="password" id="password" required>
					</p>

					<div class="action-buttons">
						<input class="button" type="submit" value="Login">
					</div>
				</form>
				<?php return ob_get_clean();
			}
		);
	break;

	case 'POST':
		// Login attempt.
		$name = Req\post('name');
		$password = Req\post('password');
		$name = $_POST['name'] ?? null;

		if ($name === null || $password === null)
		{
			http_response_code(400);

			?>
				<p>Please supply the username and password of the account you wish to log in to.</p>
				<a href="./login.php">Try again?</a>
			<?php

			exit;
		}

		$user = $userManager->authenticate($name, $password);

		if ($user === null)
		{
			// Given that this user system is for a management interface, security-wise we should
			// offer the client very little information: if we tell them "incorrect password", they
			// know that they've hit an account that exists.
			// 
			// In a more general system, we would provide that kind of helpful feedback, but in this
			// scenario we deliberately choose not to.
			http_response_code(401);

			?>
				<p>Incorrect username or password.</p>
				<a href="./login.php">Try again?</a>
			<?php

			exit;
		}

		// Grant the user a login session.
		Session\setUser($user);

		// Redirect to the management page.
		http_response_code(303);
		header('Location: ./manage.php');

		?>
			<p>
				Hello, <?php htmlspecialchars($name) ?>! You are now being redirected to the
				management interface.
			</p>

			<a href="./manage.php">...Or go there manually, if it isn't loading.</a>
		<?php
	break;

	default:
		http_response_code(405);
	break;
}
