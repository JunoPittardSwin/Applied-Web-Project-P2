<?php declare(strict_types=1); 

require_once(__DIR__ . '/lib/UserManager.php');
require_once(__DIR__ . '/lib/EoiManager.php');
require_once(__DIR__ . '/lib/Req.php');
require_once(__DIR__ . '/lib/Session.php');
require_once(__DIR__ . '/settings.php');

// Make sure we're authenticated before anything else!
$userManager = new UserManager($db);
$user = Session\getUserOrLogin($userManager);

// See if we're viewing a specific application, or grab the filter settings etc.
$form = new Req\FormContext($_GET);

/** @var ?int A specific EOI ID to view fullscreen. */
$eoiIdToView = $form->input(
	readableName: 'EOI ID to view',
	key: 'eoiIdToView',
	required: false,
	regex: '/^[0-9]+$/',
	mapValue: intval(...)
);

/** @var ?string A specific job listing to filter results against. */
$filterJobRef = $form->input(
	readableName: 'Job Reference ID',
	key: 'jobRefId',
	required: false,
	regex: '/^J[0-9]{4}$/'
);

function displayEoiTable(
	callable $captionWriter,
	array $submissions
)
{
	?>
	<table>
		<?php if ($captionWriter !== null)
		{
			?>
			<caption><?php $captionWriter(); ?></caption>
			<?php
		} ?>
		
		<thead>
			<tr>
				<th>Ref Num.</th>
				<th>Job Ref. Num</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email Address</th>
				<th>Ph. Number</th>
				<th>Actions</th>
			</tr>
		</thead>

		<tbody>
			<?php
			foreach ($submissions as $eoi)
			{
				?>
				<tr>
					<td>
						<a href="?eoiIdToView=<?= strval($eoi->id) ?>"><?= strval($eoi->id) ?></a>
					</td>
					<td><?= htmlspecialchars($eoi->jobReferenceId) ?></td>
					<td><?= htmlspecialchars($eoi->lastName) ?></td>
					<td><?= htmlspecialchars($eoi->firstName) ?></td>
					<td><?= htmlspecialchars($eoi->emailAddress) ?></td>
					<td><?= htmlspecialchars($eoi->phoneNumber) ?></td>
					<td>
						<form action="./api/eoi/delete.php" method="post">
							<input type="hidden" name="eoiId" value="<?= strval($eoi->id) ?>">
							<input type="submit" value="Delete" class="button">
						</form>
					</td>
				</tr>
				<?php
			}
			?>
		</tbody>
	</table>
	<?php
}

$eoiManager = new EoiManager($db);

/** @var ?Eoi */
$eoi = null;

if ($eoiIdToView !== null)
{
	$eoi = $eoiManager->getEoi($eoiIdToView);

	if ($eoi === null)
	{
		// If we're viewing a non-existent EOI, just redirect to the main page as it must've been
		// deleted.
		http_response_code(303);
		header('Location: /manage.php');

		exit;
	}
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Document</title>
	<link rel="stylesheet" href="./styles/style.css">
</head>
<body>
	<?php include(__DIR__ . '/header.inc'); ?>

	<main>
		<article>
			<?php
			if ($eoi === null)
			{
				?>
				<p>
					Welcome to the administration dashboard, <?= htmlspecialchars($user->name) ?>!
				</p>

				<section>
					<h2>Expressions of Interest</h2>

					<h3>New</h3>
					<?php displayEoiTable(
						captionWriter: fn() => print('Expressions of Interest that haven\'t been categorised yet.'),
						submissions: $eoiManager->getSubmissions(
							forJobRef: $filterJobRef,
							withStatus: EoiStatus::New,
							withSkills: []
						)
					) ?>

					<h3>Current</h3>
					<?php displayEoiTable(
						captionWriter: fn() => print('Expressions of Interest that are... Current? Whatever that means?'),
						submissions: $eoiManager->getSubmissions(
							forJobRef: $filterJobRef,
							withStatus: EoiStatus::Current,
							withSkills: []
						)
					) ?>

					<h3>Final</h3>
					<?php displayEoiTable(
						captionWriter: fn() => print('Expressions of Interest that made it into the final round (??? I dunno)'),
						submissions: $eoiManager->getSubmissions(
							forJobRef: $filterJobRef,
							withStatus: EoiStatus::Final,
							withSkills: []
						)
					) ?>

				</section>
				<?php
			}
			else
			{
				?>
				<h1>
					EOI <strong><?= strval($eoi->id) ?></strong> for <?= htmlspecialchars($eoi->jobReferenceId) ?> by <?= htmlspecialchars($eoi->firstName) ?> (<?= htmlspecialchars($eoi->status->value) ?>)
				</h1>

				<p>
					<?php
					$localDateTime = $eoi->submissionTimestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));
					?>

					Submitted on
					<time datetime="<?= $eoi->submissionTimestamp->format('c') ?>">
						<?= $localDateTime->format('d/m/Y') ?> at <?= $localDateTime->format('h:ia') ?>
					</time>
				</p>

				<dl>
					<dt>First Name</dt>
					<dd><?= htmlspecialchars($eoi->firstName) ?></dd>
					<dt>Last Name</dt>
					<dd><?= htmlspecialchars($eoi->lastName) ?></dd>
					<dt>Email Address</dt>
					<dd><?= htmlspecialchars($eoi->emailAddress) ?></dd>
					<dt>Phone Number</dt>
					<dd><?= htmlspecialchars($eoi->phoneNumber) ?></dd>
					<dt>Gender</dt>
					<dd><?= htmlspecialchars(ucfirst($eoi->gender) ?? 'Unspecified') ?></dd>
					<dt>Date Of Birth</dt>
					<dd><time><?= htmlspecialchars($eoi->dateOfBirth?->format('d/m/Y') ?? 'Unspecified') ?></time></dd>
				</dl>

				<section>
					<h2>Skills</h2>
					<h3>Searchable Skills</h3>
					<ul>
						<?php
						foreach ($eoi->skills as $skill)
						{
							?>
							<li><?= htmlspecialchars($skill) ?></li>
							<?php
						}
						?>
					</ul>

					<h3>Other Skills</h3>
					<p>
						<?= htmlspecialchars($eoi->commentsAndOtherSkills ?? 'Not specified.') ?>
					</p>
				</section>

				<h2>Location</h2>
				<address>
					<dl>
						<dt>State</dt>
						<dd><?= htmlspecialchars($eoi->state->value) ?></dd>
						<dt>Suburb</dt>
						<dd><?= htmlspecialchars($eoi->suburb ?? 'Not specified.') ?></dd>
						<dt>Postcode</dt>
						<dd><?= strval($eoi->postcode ?? 'Not specified.') ?></dd>
						<dt>Street Address</dt>
						<dd><?= htmlspecialchars($eoi->streetAddress ?? 'Not specified.') ?></dd>
					</dl>
				</address>

				<h2>Management Actions</h2>
				<nav>
					<form action="./api/eoi/delete.php" method="post">
						<input type="hidden" name="eoiId" value="<?= strval($eoi->id) ?>">
						<input type="submit" value="Delete" class="button">
					</form>
				</nav>
				<?php
			}
			?>
		</article>
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
