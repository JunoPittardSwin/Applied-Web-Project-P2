<?php declare(strict_types=1);

namespace Templates\Manage;

use JobListing;

use function Templates\document;

require_once(__DIR__ . '/../document.php');

/**
 * View the details about a specific active job listing.
 *
 * @param JobListing $job The job listing to be viewed.
 * @return string The HTML to display.
 */
function viewJobListing(JobListing $job): string
{
	return document(
		title: "{$job->title} (Ref: {$job->ref})",
		description: "Details about the job listing with the reference number {$job->ref}.",
		mainContent: function() use ($job)
		{
			ob_start();

			?>
			<article id="content">
				<a href="?">Back to Overview</a>
	
				<h1><?= htmlspecialchars($job->ref) ?>: <?= htmlspecialchars($job->title) ?></h1>
	
				<section>
					<h2>Management Actions</h2>

					<form action="./api/eoi/delete.php" method="post">
						<input
							type="hidden"
							name="reference"
							value="<?= htmlspecialchars($job->ref, ENT_QUOTES) ?>"
						>
						<button type="submit">Delete ALL EOIs for this job</button>
					</form>
				</section>
			</article>
			<?php

			return ob_get_clean();
		}
	);
}
