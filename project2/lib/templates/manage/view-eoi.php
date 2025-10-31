<?php declare(strict_types=1);

/*
filename: view-eoi.php
author: Ashlyn Randall
created: 23/10/2025
last modified: 27/10/2025
description: HTML template for viewing the details about a specific EOI as a HR manager.
*/

namespace Templates\Manage;

use \Eoi;
use \DateTimeZone;
use EoiStatus;

use function Templates\document;

require_once(__DIR__ . '/../document.php');

/**
 * View the details about a specific Expression of Interest submitted.
 *
 * @param Eoi $eoi The EOI to be viewed.
 * @return string The HTML to display.
 */
function viewEoi(Eoi $eoi): string
{
	return document(
		title: 'EOI ' . strval($eoi->id),
		description: 'EOI submitted by ' . $eoi->firstName,
		mainContent: function() use ($eoi)
		{
			$localDateTime = $eoi->submissionTimestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));
			ob_start();

			?>
			<article id="content">
				<a href="?">Back to Overview</a>
	
				<h1>
					<?= htmlspecialchars($eoi->status->value) ?>: EOI for <?= htmlspecialchars($eoi->jobReferenceId) ?> by <?= htmlspecialchars($eoi->firstName) ?>
				</h1>
	
				<p>
					Reference Number <strong><?= strval($eoi->id) ?></strong>,
					Submitted on <time datetime="<?= $eoi->submissionTimestamp->format('c') ?>"><?= $localDateTime->format('d/m/Y') ?> at <?= $localDateTime->format('h:ia') ?></time>.
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
					<dd><?= htmlspecialchars(ucfirst($eoi->gender) ?? 'Not specified') ?></dd>
					<dt>Date Of Birth</dt>
					<dd>
						<?php if ($eoi->dateOfBirth !== null): ?>
							<time datetime="<?= htmlspecialchars($eoi->dateOfBirth->format('c'), ENT_QUOTES) ?>">
								<?= htmlspecialchars($eoi->dateOfBirth->format('d/m/Y')) ?>
							</time>
						<?php else: ?>
							Not specified
						<?php endif ?>
					</dd>
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
					<form action="./api/eoi/change-status.php" method="post">
						<input type="hidden" name="eoiId" value="<?= strval($eoi->id) ?>">

						<fieldset>
							<legend>Update Status</legend>
							<p>Change the status of this application in the review process.</p>

							<?php foreach (EoiStatus::cases() as $status): ?>
								<?php $statusElementId = 'radio-status-' . htmlspecialchars($status->value, ENT_QUOTES) ?>

								<label for="<?= $statusElementId ?>">
									<input
										type="radio"
										name="status"
										id="<?= $statusElementId ?>"
										value="<?= htmlspecialchars($status->value, ENT_QUOTES) ?>"
										<?php if ($status === $eoi->status): ?>
											checked
										<?php endif ?>
									>

									<?= htmlspecialchars($status->value) ?>
								</label>
							<?php endforeach ?>
		
							<input type="submit" value="Set Status" class="button">
						</fieldset>
					</form>
	
					<form action="./api/eoi/delete.php" method="post">
						<fieldset>
							<legend>Delete</legend>
							<p>Remove this expression of interest from the system.</p>
		
							<input type="hidden" name="eoiId" value="<?= strval($eoi->id) ?>">
							<input type="submit" value="Delete" class="button">
						</fieldset>
					</form>
				</nav>
			</article>
			<?php

			return ob_get_clean();
		}
	);
}
