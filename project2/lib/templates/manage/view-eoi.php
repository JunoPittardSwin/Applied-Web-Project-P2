<?php declare(strict_types=1);

namespace Templates\Manage;

use \Eoi;
use \DateTimeZone;
use EoiStatus;

/**
 * View the details about a specific Expression of Interest submitted.
 *
 * @param Eoi $eoi The EOI to be viewed.
 * @return string The HTML to display.
 */
function viewEoi(Eoi $eoi): string
{
	$localDateTime = $eoi->submissionTimestamp->setTimezone(new DateTimeZone(date_default_timezone_get()));
	ob_start();

	?>
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
		<form action="./api/eoi/change-status.php" method="post">
			<h3>Update Status</h3>
			<p>Change the status of this application in the review process.</p>

			<input type="hidden" name="eoiId" value="<?= strval($eoi->id) ?>">

			<select name="status" required>
				<?php foreach (array_filter(EoiStatus::cases(), fn(EoiStatus $case) => ($case !== $eoi->status)) as $status): ?>
					<option value="<?= htmlspecialchars($status->value, ENT_QUOTES) ?>">
						<?= htmlspecialchars($status->value) ?>
					</option>
				<?php endforeach ?>
			</select>

			<input type="submit" value="Set Status" class="button">
		</form>

		<form action="./api/eoi/delete.php" method="post">
			<h3>Delete</h3>
			<p>Remove this expression of interest from the system.</p>

			<input type="hidden" name="eoiId" value="<?= strval($eoi->id) ?>">
			<input type="submit" value="Delete" class="button">
		</form>
	</nav>
	<?php

	return ob_get_clean();
}
