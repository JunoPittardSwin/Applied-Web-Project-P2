<?php declare(strict_types=1);

use function Req\post;
use function Req\postOrBail;

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
	http_response_code(303);
	header('Location: ./apply.php');

	exit;
}

require_once(__DIR__ . '/lib/Req.php');

$jobReferenceId = postOrBail('reference');

$firstName = postOrBail('first_name');
$lastName = postOrBail('last_name');
$emailAddress = postOrBail('email');
$phoneNumber = postOrBail('phone');
$gender = post('gender');
$dateOfBirthString = post('dob');
$dateOfBirth = null;

if ($dateOfBirthString !== null)
{
	try
	{
		$dateOfBirth = new DateTimeImmutable($dateOfBirthString);
	}
	catch (DateMalformedStringException $ex)
	{
		http_response_code(400);
		
		?>
		<p>Didn't understand the format you wrote your date of birth in.</p>
		<p>Try writing it like so: <em>DD/MM/YYYY</em>.</p>
		
		<a href="./apply.php">Retry your application?</a>
		<?php

		exit;
	}
}

$state = postOrBail('state');
$streetAddress = post('street_address');
$suburb = post('suburb');
$postCodeString = post('suburb');

// [reference] => J0123
// [first_name] => Christopher
// [last_name] => Null
// [dob] => 01/01/2000
// [gender] => male
// [street_address] => 
// [suburb] => 
// [state] => 
// [postcode] => 
// [email] => 
// [phone] => 
// [other_skills] => 

// ...

require_once(__DIR__ . '/lib/EoiManager.php');
require_once(__DIR__ . '/settings.php');

$eoiManager = new EoiManager($db);

$eoiManager->submitEoi(
	// jobReferenceId: $jobReferenceId,
	firstName: 
);
