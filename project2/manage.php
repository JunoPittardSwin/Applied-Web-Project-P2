<?php declare(strict_types=1);

use Req\InputMapFailedException;
use DB\SortDirection;

use function Templates\document;
use function Templates\Manage\eoiTable;
use function Templates\Manage\viewEoi;
use function Templates\selectInput;
use function Templates\textInput;

require_once(__DIR__ . '/lib/UserManager.php');
require_once(__DIR__ . '/lib/EoiManager.php');
require_once(__DIR__ . '/lib/Req.php');
require_once(__DIR__ . '/lib/Session.php');
require_once(__DIR__ . '/settings.php');
require_once(__DIR__ . '/lib/templates/document.php');

// Make sure we're authenticated before anything else!
$userManager = new UserManager($db);
$user = Session\getUserOrLogin($userManager);

// See if we're viewing a specific application.
$form = new Req\FormContext($_GET);

/** @var ?int A specific EOI ID to view fullscreen. */
$eoiIdToView = $form->input(
	readableName: 'EOI ID to view',
	key: 'eoiIdToView',
	required: false,
	regex: '/^[0-9]+$/',
	mapValue: intval(...)
);

$eoiManager = new EoiManager($db);

if ($eoiIdToView !== null)
{
	$eoi = $eoiManager->getEoi($eoiIdToView);

	if ($eoi === null)
	{
		http_response_code(404);

		echo document(
			title: 'EOI ' . strval($eoiIdToView) . ' not found',
			mainContent: function() use ($eoiIdToView)
			{
				ob_start();
				?>
				<article id="content">
					<h1>The EOI with ID <?= strval($eoiIdToView) ?> doesn't seem to exist</h1>
					<a href="?">Back to Overview</a>
				</article>
				<?php
				return ob_get_clean();
			}
		);

		exit;
	}

	echo document(
		title: 'EOI ' . strval($eoi->id),
		description: 'EOI submitted by ' . $eoi->firstName,
		mainContent: function() use ($eoi)
		{
			require_once(__DIR__ . '/lib/templates/manage/view-eoi.php');
			
			return '<article id="content">' . 
				viewEoi($eoi) .
			'</article>';
		}
	);

	exit;
}

function eoiSortByName(EoiSortBy $case): string
{
	return match ($case)
	{
		EoiSortBy::JobReferenceId => 'Job Reference ID',
		EoiSortBy::Recency => 'Recency',
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
	mainContent: function() use ($user, $eoiManager)
	{
		require_once(__DIR__ . '/lib/templates/manage/eoi-table.php');
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

		ob_start();

		?>
		<article id="content">
			<p>
				Welcome to the administration dashboard, <?= htmlspecialchars($user->name) ?>!
			</p>

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

				<h3>New</h3>
				<?= eoiTable(
					caption: 'Expressions of Interest that haven\'t been categorised yet.',
					submissions: $eoiManager->getSubmissions(
						forJobRef: $filterJobRef,
						withFirstName: $filterFirstName,
						withLastName: $filterLastName,
						withEmailAddress: $filterEmailAddress,
						withStatus: EoiStatus::New,
						withSkills: [],
						sortBy: $sortBy,
						sortDirection: $sortDirection
					)
				) ?>

				<h3>Current</h3>
				<?= eoiTable(
					caption: 'Expressions of Interest that are... Current? Whatever that means?',
					submissions: $eoiManager->getSubmissions(
						forJobRef: $filterJobRef,
						withFirstName: $filterFirstName,
						withLastName: $filterLastName,
						withEmailAddress: $filterEmailAddress,
						withStatus: EoiStatus::Current,
						withSkills: [],
						sortBy: $sortBy,
						sortDirection: $sortDirection
					)
				) ?>

				<h3>Final</h3>
				<?= eoiTable(
					caption: 'Expressions of Interest that made it into the final round (??? I dunno)',
					submissions: $eoiManager->getSubmissions(
						forJobRef: $filterJobRef,
						withFirstName: $filterFirstName,
						withLastName: $filterLastName,
						withEmailAddress: $filterEmailAddress,
						withStatus: EoiStatus::Final,
						withSkills: [],
						sortBy: $sortBy,
						sortDirection: $sortDirection
					)
				) ?>

			</section>

			<section>
				<h2>Job Listings</h2>

				<form action="./api/eoi/delete.php" method="post">
					<h3>Delete all EOIs for a Job</h3>

					<?= textInput(
						readableName: 'Job Reference ID',
						key: 'reference',
						required: true
					) ?>

					<button type="submit">Delete all EOIs for this Job ID</button>
				</form>
			</section>
		</article>
		<?php

		return ob_get_clean();
	}
);
