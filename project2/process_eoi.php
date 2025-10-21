<?php declare(strict_types=1);

use Req\FormContext;

if ($_SERVER['REQUEST_METHOD'] !== 'POST')
{
	http_response_code(303);
	header('Location: ./apply.php');

	exit;
}

require_once(__DIR__ . '/lib/Req.php');

$form = FormContext::fromPostBody();

$jobReferenceId = $form->input(
	readableName: 'Job Reference ID',
	key: 'reference',
	regex: '/^J[0-9]{4}$/',
	required: true,
);

$firstName = $form->input(
	readableName: 'First Name',
	key: 'first_name',
	required: true,
	maxLength: 32,
);

$lastName = $form->input(
	readableName: 'Last Name',
	key: 'last_name',
	required: true,
	maxLength: 32,
);

$emailAddress = $form->input(
	readableName: 'Email Address',
	key: 'email',
	filterMode: FILTER_VALIDATE_EMAIL,
	required: true,
	maxLength: 64,
);

$phoneNumber = $form->input(
	readableName: 'Phone Number',
	key: 'phone',
	required: true,
	minLength: 8,
	maxLength: 20
);

$gender = $form->input(
	readableName: 'Gender',
	key: 'gender',
	required: false,
	maxLength: 16
);

$dateOfBirth = $form->input(
	readableName: 'Date of Birth',
	key: 'dob',
	required: false,
	mapValue: fn(string $dob) => new DateTimeImmutable($dob)
);

$otherSkills = $form->input(
	readableName: 'Other Skills',
	key: 'other_skills',
	required: false
);

$state = $form->input(
	readableName: 'State',
	key: 'state',
	regex: '/^(VIC|NSW|QLD|NT|WA|SA|TAS|ACT)$/',
	required: true
);

$streetAddress = $form->input(
	readableName: 'Street Address',
	key: 'street_address',
	required: false,
	maxLength: 24
);

$suburb = $form->input(
	readableName: 'Suburb',
	key: 'suburb',
	required: false,
	maxLength: 24
);

$postCode = $form->input(
	readableName: 'Postcode',
	key: 'postcode',
	regex: '/^[0-9]+$/',
	required: false,
	maxLength: 4,
	mapValue: intval(...)
);

$skills = $form->inputArray(
	readableName: 'Skills',
	key: 'skills',
	required: true,
	memberRegex: '/^[a-z_]+$/',
	memberMaxLength: 32,
	memberMinLength: 1,
);

if ($form->hasErrors())
{
	http_response_code(400);
	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Expression of Interest | Watertight CyberSec</title>
		<link rel="stylesheet" href="./css/styles.css">
	</head>
	<body>
		<?php include(__DIR__ . '/header.inc'); ?>

		<header id="hero-container">
			<div id="hero">
				<h1>Please try again</h1>
				<p>
					Thanks for submitting your expression of interest. Unfortunately, there's a few
					issues with it listed below. Please fix these issues and submit again, we'd
					love to hear from you!
				</p>
			</div>
		</header>

		<main>
			<article>
				<h2>Submission Issues</h2>
				<ul>
					<?php foreach ($form->htmlErrorList as $error)
					{
						?>
						<li><?= $error ?></li>
						<?php
					}
					?>
				</ul>

				<a class="button" href="./apply.php">Retry your application</a>
			</article>
		</main>

		<?php include(__DIR__ . '/footer.inc'); ?>
	</body>
	</html>

	<?php
	exit;
}

require_once(__DIR__ . '/lib/EoiManager.php');
require_once(__DIR__ . '/settings.php');

$eoiManager = new EoiManager($db);

$eoiManager->submitEoi(
	// jobReferenceId: $jobReferenceId,
	firstName: $firstName,
	lastName: $lastName,
	emailAddress: $emailAddress,
	phoneNumber: $phoneNumber,
	gender: $gender,
	dateOfBirth: $dateOfBirth,
	state: $state,
	streetAddress: $streetAddress,
	suburb: $suburb,
	postcode: $postCode,
	skills: $skills,
	commentsAndOtherSkills: $otherSkills
);

echo '<pre>';
print_r($_POST);
