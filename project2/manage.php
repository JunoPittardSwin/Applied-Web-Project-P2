<?php declare(strict_types=1);

use function Templates\document;
use function Templates\Manage\eoiTable;
use function Templates\Manage\viewEoi;

require_once(__DIR__ . '/lib/UserManager.php');
require_once(__DIR__ . '/lib/EoiManager.php');
require_once(__DIR__ . '/lib/Req.php');
require_once(__DIR__ . '/lib/Session.php');
require_once(__DIR__ . '/settings.php');
require_once(__DIR__ . '/lib/templates/document.php');

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

$eoiManager = new EoiManager($db);

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

/** @var ?string A specific job listing to filter results against. */
$filterJobRef = $form->input(
	readableName: 'Job Reference ID',
	key: 'jobRefId',
	required: false,
	regex: '/^J[0-9]{4}$/'
);

echo document(
	title: 'Manage Jobs',
	description: 'Manage job listings and expressions of interest.',
	mainContent: function() use ($user, $eoiManager, $filterJobRef)
	{
		require_once(__DIR__ . '/lib/templates/manage/eoi-table.php');
		ob_start();

		?>
		<article id="content">
			<p>
				Welcome to the administration dashboard, <?= htmlspecialchars($user->name) ?>!
			</p>

			<section>
				<h2>Expressions of Interest</h2>

				<h3>New</h3>
				<?= eoiTable(
					caption: 'Expressions of Interest that haven\'t been categorised yet.',
					submissions: $eoiManager->getSubmissions(
						forJobRef: $filterJobRef,
						withStatus: EoiStatus::New,
						withSkills: []
					)
				) ?>

				<h3>Current</h3>
				<?= eoiTable(
					caption: 'Expressions of Interest that are... Current? Whatever that means?',
					submissions: $eoiManager->getSubmissions(
						forJobRef: $filterJobRef,
						withStatus: EoiStatus::Current,
						withSkills: []
					)
				) ?>

				<h3>Final</h3>
				<?= eoiTable(
					caption: 'Expressions of Interest that made it into the final round (??? I dunno)',
					submissions: $eoiManager->getSubmissions(
						forJobRef: $filterJobRef,
						withStatus: EoiStatus::Final,
						withSkills: []
					)
				) ?>

			</section>
		</article>
		<?php

		return ob_get_clean();
	}
);
