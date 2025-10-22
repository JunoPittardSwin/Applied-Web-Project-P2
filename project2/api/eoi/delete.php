<?php declare(strict_types=1);

use Req\FormContext;

/**
 * Delete an EOI by its ref number.
 * 
 * # Form Body
 * - `eoiId`: ID of the EOI application to delete.
 */

// This is a POST or DELETE route.
if (!in_array($_SERVER['REQUEST_METHOD'], ['POST', 'DELETE']))
{
	http_response_code(405);
	exit;
}

require_once(__DIR__ . '/../../lib/UserManager.php');
require_once(__DIR__ . '/../../lib/EoiManager.php');
require_once(__DIR__ . '/../../lib/Req.php');
require_once(__DIR__ . '/../../lib/Session.php');
require_once(__DIR__ . '/../../settings.php');

// Ensure the user is authenticated.
Session\getUserOrLogin(new UserManager($db));

// Determine which EOI they want to remove.
$form = FormContext::fromPostBody();

$eoiId = $form->input(
	readableName: 'EOI ID',
	key: 'eoiId',
	required: true,
	regex: '/^[0-9]+$/',
	mapValue: intval(...)
);

if ($form->hasErrors())
{
	http_response_code(400);
	
	echo '<h1>400 Bad Request</h1>';
	echo '<ul>';
	foreach ($form->htmlErrorList as $error)
	{
		echo "<li>$error</li>";
	}
	echo '</ul>';

	exit;
}

$eoiManager = new EoiManager($db);
$eoiManager->deleteEoi($eoiId);

http_response_code(303);
header('Location: /manage.php');
