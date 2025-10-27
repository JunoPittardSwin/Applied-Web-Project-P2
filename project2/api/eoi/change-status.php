<?php declare(strict_types=1);

use Req\FormContext;
use Req\InputMapFailedException;

/**
 * Update the status of an EOI.
 * 
 * # Form Body
 * - `eoiId`: ID of the EOI application to modify.
 * - `status`: The status to set for it.
 */

// This is a POST route.
if ($_SERVER['REQUEST_METHOD'] !== 'POST')
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

$form = FormContext::fromPostBody();

$eoiId = $form->input(
	readableName: 'EOI ID',
	key: 'eoiId',
	required: true,
	regex: '/^[0-9]+$/',
	mapValue: intval(...)
);

$status = $form->input(
	readableName: 'Status to set',
	key: 'status',
	required: true,
	mapValue: fn(string $status) => EoiStatus::tryFrom($status)
		?? throw new InputMapFailedException('is not a valid status.')
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

$success = $eoiManager->setStatusOf(id: $eoiId, status: $status);

if (!$success)
{
	http_response_code(404);
	echo '<h1>404 Not Found</h1>';
	
	exit;
}

http_response_code(303);
header('Location: ../../manage.php?eoiIdToView=' . strval($eoiId));
