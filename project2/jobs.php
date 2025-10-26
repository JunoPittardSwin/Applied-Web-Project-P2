<?php declare(strict_types=1);

use Req\FormContext;

use function Templates\textInput;

require_once(__DIR__ . '/lib/JobManager.php');
require_once(__DIR__ . '/lib/db/SortDirection.php');
require_once(__DIR__ . '/lib/Req.php');
require_once(__DIR__ . '/lib/templates/text-input.php');
require_once(__DIR__ . '/settings.php');

$jobManager = new JobManager($db);

if ($jobManager->getJobListingCount() === 0)
{
	include_once(__DIR__ . '/lib/DefaultData.php');
	defaultJobs($jobManager);
}

/**
 * Sorting and filtering options for the job list.
 */
$form = new FormContext($_GET);

/** @var ?string A query for searching the job list. */
$searchQuery = $form->input(
	readableName: 'Search Query',
	key: 'q',
	required: false,
);

$jobListings = $jobManager->getAllJobListings($searchQuery);

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Juno Pittard">
	<meta name="description" content="Applied Web Project Part 1 - Jobs Page">
	<meta name="keywords" content="cybersecurity, jobs, hiring">
	<title>Open Jobs | Watertight Recruitment</title>
	<link rel="stylesheet" href="./styles/style.css">
</head>
<body>
	<?php include(__DIR__ . '/header.inc'); ?>

	<!--
		Optional full-screen content for the most important page content, if applicable.
		
		Page-specific styling may override the `background-image` of this element to a relevant
		image.
	-->
	<header id="hero-container" class="hero-background-jobs">
		<div id="hero">
			<h1>We're Hiring!</h1>
			<p>
				See our currently open positions below
			</p>
		</div>
	</header>

	<!-- Main content of the page. -->
	<main>
		<!-- theoretical reference number format: Job, Internal=0 Contractor=1, 1 digit for Team ID, 2 digits for team position.  -->
		<article id="content" class="listing">
			<form action="" method="get" class="search" role="search">
				<div class="search-bar">
					<input
						type="search"
						name="q"
						id="input-jobTitle"
						placeholder="Search the job list..."
						value="<?= ($searchQuery !== null) ? htmlspecialchars($searchQuery, ENT_QUOTES) : '' ?>"
					>
					<button type="submit">Search</button>
				</div>
			</form>

			<?php foreach ($jobListings as $job): ?>
				<section>
					<h2><?= htmlspecialchars($job->title) ?> (REF:<?= htmlspecialchars($job->ref) ?>)</h2>

					<?php if ($job->asideInfoHtml !== null): ?>
						<aside>
							<?= $job->asideInfoHtml ?>
						</aside>
					<?php endif ?>

					<em>
						Salary: $<?= number_format($job->salaryLowBracket, 2) ?> - $<?= number_format($job->salaryHighBracket, 2) ?> p/a
						<br>
						Reporting Line: <?= htmlspecialchars($job->reportingLine) ?>
					</em>

					<h3>About the role</h3>
					<p><?= $job->aboutHtml ?></p>

					<h3>Essential requirements:</h3>
					<ol>
						<?php foreach ($job->essentialRequirements as $requirement): ?>
							<li><?= htmlspecialchars($requirement) ?></li>
						<?php endforeach ?>
					</ol>

					<h3>Preferred requirements:</h3>
					<ol>
						<?php foreach ($job->preferredRequirements as $requirement): ?>
							<li><?= htmlspecialchars($requirement) ?></li>
						<?php endforeach ?>
					</ol>

					<a href="./apply.php?reference=<?= htmlspecialchars($job->ref, ENT_QUOTES) ?>" class="button">
						Apply for <?= htmlspecialchars($job->ref) ?>
					</a>
				</section>
			<?php endforeach ?>
		</article>
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
			