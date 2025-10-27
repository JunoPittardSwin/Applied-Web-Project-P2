<?php declare(strict_types=1);

use Req\InputMapFailedException;
use DB\SortDirection;

use function Templates\document;
use function Templates\Manage\viewEoi;
use function Templates\Manage\viewJobListing;
use function Templates\selectInput;
use function Templates\textInput;

require_once(__DIR__ . '/lib/UserManager.php');
require_once(__DIR__ . '/lib/EoiManager.php');
require_once(__DIR__ . '/lib/JobManager.php');
require_once(__DIR__ . '/lib/Req.php');
require_once(__DIR__ . '/lib/Session.php');
require_once(__DIR__ . '/settings.php');
require_once(__DIR__ . '/lib/templates/document.php');

// Make sure we're authenticated before anything else!
$userManager = new UserManager($db);
$user = Session\getUserOrLogin($userManager);

$eoiManager = new EoiManager($db);
$jobManager = new JobManager($db);

// See if we're viewing a specific application.
$viewDetailsForm = new Req\FormContext($_GET);

/** @var ?Eoi A specific EOI to view fullscreen. */
$eoiToView = $viewDetailsForm->input(
	readableName: 'EOI ID to view',
	key: 'eoiIdToView',
	required: false,
	regex: '/^[0-9]+$/',
	mapValue: fn(string $id) => $eoiManager->getEoi(intval($id))
		?? throw new InputMapFailedException('was not found in the system.')
);

/** @var ?JobListing A specific job listing to view fullscreen. */
$jobToView = $viewDetailsForm->input(
	readableName: 'Job Reference ID to view',
	key: 'jobRefToView',
	required: false,
	regex: '/^J[0-9]{4}$/',
	mapValue: fn(string $ref) => $jobManager->getJobListing($ref)
		?? throw new InputMapFailedException('was not found in the system.')
);

switch (true)
{
	case ($eoiToView !== null):
		require_once(__DIR__ . '/lib/templates/manage/view-eoi.php');
		exit(viewEoi($eoiToView));

	case ($jobToView !== null):
		require_once(__DIR__ . '/lib/templates/manage/view-job-listing.php');
		exit(viewJobListing($jobToView));
};

function eoiSortByName(EoiSortBy $case): string
{
	return match ($case)
	{
		EoiSortBy::JobReferenceId => 'Job Reference ID',
		EoiSortBy::Recency => 'Recency',
		EoiSortBy::Status => 'Application Status',
	};
}

function sortDirectionName(SortDirection $case): string
{
	return match ($case)
	{
		SortDirection::Ascending => 'Ascending Order',
		SortDirection::Descending => 'Descending Order',
	};
}

echo document(
	title: 'Manage Jobs',
	description: 'Manage job listings and expressions of interest.',
	mainContent: function() use ($user, $eoiManager, $jobManager, $viewDetailsForm)
	{
		require_once(__DIR__ . '/lib/templates/text-input.php');
		require_once(__DIR__ . '/lib/templates/select-input.php');

		$form = new Req\FormContext($_GET);

		/** @var ?string A specific job listing to filter results against. */
		$filterJobRef = $form->input(
			readableName: 'Job Reference ID',
			key: 'filterJobRef',
			required: false,
			regex: '/^J[0-9]{4}$/'
		);

		/** @var ?string An email address to filter applications by. */
		$filterEmailAddress = $form->input(
			readableName: 'Email Address',
			key: 'filterEmailAddress',
			required: false,
			filterMode: FILTER_VALIDATE_EMAIL
		);

		/** @var ?string An applicant's last name to filter by. */
		$filterLastName = $form->input(
			readableName: 'Last Name',
			key: 'filterLastName',
			required: false,
		);

		/** @var ?string An applicant's first name to filter by. */
		$filterFirstName = $form->input(
			readableName: 'First Name',
			key: 'filterFirstName',
			required: false,
		);

		/** @var EoiSortBy Field to sort the applications by. */
		$sortBy = $form->input(
			readableName: 'Sort By',
			key: 'sortBy',
			required: false,
			mapValue: fn(string $value) => EoiSortBy::tryFrom($value)
				?? throw new InputMapFailedException('is not a valid sorting method.')
		) ?? EoiSortBy::Recency;

		/** @var SortDirection The direction in which to sort the fields. */
		$sortDirection = $form->input(
			readableName: 'Sort Direction',
			key: 'sortDirection',
			required: false,
			mapValue: fn(string $value) => SortDirection::tryFrom($value)
				?? throw new InputMapFailedException('is not a valid sorting direction.')
		) ?? SortDirection::Descending;

		$submissions = $eoiManager->getSubmissions(
			forJobRef: $filterJobRef,
			withFirstName: $filterFirstName,
			withLastName: $filterLastName,
			withEmailAddress: $filterEmailAddress,
			withSkills: [],
			sortBy: $sortBy,
			sortDirection: $sortDirection
		);

		ob_start();

		?>
		<article id="content">
			<h1>
				Welcome to the administration dashboard, <?= htmlspecialchars($user->name) ?>!
			</h1>

			<?php if ($viewDetailsForm->hasErrors()): ?>
				<p>You've been taken to the dashboard due to the following issue(s):</p>
				<ul>
					<?php foreach ($viewDetailsForm->htmlErrorList as $error): ?>
						<li><?= $error ?></li>
					<?php endforeach ?>
				</ul>
			<?php endif ?>

			<section>
				<h2>Expressions of Interest</h2>

				<form action="" method="get" class="search">
					<?php if ($form->hasErrors()): ?>
						<fieldset class="issues">
							<legend>Search Issues</legend>
							<ul>
								<?php foreach ($form->htmlErrorList as $error): ?>
									<li><?= $error ?></li>
								<?php endforeach ?>
							</ul>
						</fieldset>
					<?php endif ?>

					<section class="filters-and-sorting">
						<fieldset class="filters">
							<legend>Filters</legend>

							<p>
								<?= textInput(
									readableName: 'Job Reference ID',
									key: 'filterJobRef',
									required: false,
									initialValue: $filterJobRef
								) ?>
							</p>

							<p>
								<?= textInput(
									readableName: 'Email Address',
									key: 'filterEmailAddress',
									required: false,
									initialValue: $filterEmailAddress
								) ?>
							</p>

							<p>
								<?= textInput(
									readableName: 'Last Name',
									key: 'filterLastName',
									required: false,
									initialValue: $filterLastName
								) ?>
							</p>

							<p>
								<?= textInput(
									readableName: 'First Name',
									key: 'filterFirstName',
									required: false,
									initialValue: $filterFirstName
								) ?>
							</p>
						</fieldset>

						<fieldset class="sorting">
							<legend>Sort</legend>

							<p>
								<?= selectInput(
									readableName: 'Sort By',
									key: 'sortBy',
									options: [
										eoiSortByName(EoiSortBy::JobReferenceId) => EoiSortBy::JobReferenceId->value,
										eoiSortByName(EoiSortBy::Recency) => EoiSortBy::Recency->value,
										eoiSortByName(EoiSortBy::Status) => EoiSortBy::Status->value,
									],
									initialChoiceName: eoiSortByName($sortBy),
								) ?>
							</p>
							
							<p>
								<?= selectInput(
									readableName: 'Sort Direction',
									key: 'sortDirection',
									options: [
										sortDirectionName(SortDirection::Descending) => SortDirection::Descending->value,
										sortDirectionName(SortDirection::Ascending) => SortDirection::Ascending->value,
									],
									initialChoiceName: sortDirectionName($sortDirection),
								) ?>
							</p>
						</fieldset>
					</section>

					<nav class="buttons">
						<button type="submit">Search</button>
					</nav>
				</form>

				<table>
					<thead>
						<tr>
							<th>Ref Num.</th>
							<th>Job Ref. Num</th>
							<th>Status</th>
							<th>Last Name</th>
							<th>First Name</th>
							<th>Email Address</th>
							<th>Ph. Number</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						<?php foreach ($submissions as $eoi): ?>
							<tr>
								<td><?= strval($eoi->id) ?></td>
								<td><?= htmlspecialchars($eoi->jobReferenceId) ?></td>
								<td><?= htmlspecialchars($eoi->status->value) ?></td>
								<td><?= htmlspecialchars($eoi->lastName) ?></td>
								<td><?= htmlspecialchars($eoi->firstName) ?></td>
								<td><?= htmlspecialchars($eoi->emailAddress) ?></td>
								<td><?= htmlspecialchars($eoi->phoneNumber) ?></td>
								<td>
									<a href="?eoiIdToView=<?= strval($eoi->id) ?>">Details</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</section>

			<section>
				<h2>Job Listings</h2>

				<table>
					<thead>
						<tr>
							<th>Ref Num.</th>
							<th>Title</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($jobManager->getAllJobListings() as $job): ?>
							<tr>
								<td><?= htmlspecialchars($job->ref) ?></td>
								<td><?= htmlspecialchars($job->title) ?></td>
								<td>
									<a href="?jobRefToView=<?= urlencode($job->ref) ?>">Edit</a>
								</td>
							</tr>
						<?php endforeach ?>
					</tbody>
				</table>
			</section>
		</article>
		<?php

		return ob_get_clean();
	}
);
