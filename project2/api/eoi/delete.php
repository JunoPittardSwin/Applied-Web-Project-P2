<?php declare(strict_types=1);

/*
filename: delete.php
author: Ashlyn Randall
created: 22/10/2025
last modified: 27/10/2025
description: REST API route for deleting one or more expressions of interest as a HR manager.
*/

use Req\FormContext;

/**
 * Delete one or more EOIs that match one or more given criteria.
 * 
 * # Form Body
 * - `eoiId`: ID of a EOI application to delete. If specified, other criteria are ignored.
 * - `jobReferenceId`: ID of a job to delete all EOIs on.
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
	required: false,
	regex: '/^[0-9]+$/',
	mapValue: intval(...)
);

$jobReferenceId = $form->input(
	readableName: 'Job Reference ID',
	key: 'reference',
	regex: '/^J[0-9]{4}$/',
	required: false,
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

if ($eoiId === null)
{
	if ($jobReferenceId === null)
	{
		http_response_code(400);

		?>
		<h1>400 Bad Request</h1>
		<p>At least one filter must be specified to bulk-delete.</p>
		<?php

		exit;
	}

	foreach ($eoiManager->getSubmissions(forJobRef: $jobReferenceId) as $eoi)
	{
		$eoiManager->deleteEoi($eoi->id);
	}
}
else
{
	$eoiManager->deleteEoi($eoiId);
}

http_response_code(303);
header('Location: ../../manage.php');
