<?php declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
	http_response_code(303);
	header('Location: ./apply.php');

	exit;
}

require_once(__DIR__ . '/lib/EoiManager.php');
require_once(__DIR__ . '/settings.php');

$eoiManager = new EoiManager($db);


