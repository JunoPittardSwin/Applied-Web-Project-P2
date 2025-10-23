<?php declare(strict_types=1);

namespace Templates\Manage;

/**
 * A table of information about zero or more expressions of interest for a job.
 *
 * @param ?string $caption
 * @param \Eoi[] $submissions
 * @return string The HTML to display.
 */
function eoiTable(array $submissions, ?string $caption = null): string
{
	ob_start();

	?>
	<table>
		<?php if ($caption !== null): ?>
			<caption><?= $caption ?></caption>
		<?php endif ?>
		
		<thead>
			<tr>
				<th>Ref Num.</th>
				<th>Job Ref. Num</th>
				<th>Last Name</th>
				<th>First Name</th>
				<th>Email Address</th>
				<th>Ph. Number</th>
			</tr>
		</thead>

		<tbody>
			<?php foreach ($submissions as $eoi): ?>
				<tr>
					<td>
						<!-- FIXME: replace this with stylesheet usage -->
						<a style="display: block;" href="?eoiIdToView=<?= strval($eoi->id) ?>"><?= strval($eoi->id) ?></a>
					</td>
					<td><?= htmlspecialchars($eoi->jobReferenceId) ?></td>
					<td><?= htmlspecialchars($eoi->lastName) ?></td>
					<td><?= htmlspecialchars($eoi->firstName) ?></td>
					<td><?= htmlspecialchars($eoi->emailAddress) ?></td>
					<td><?= htmlspecialchars($eoi->phoneNumber) ?></td>
				</tr>
			<?php endforeach ?>
		</tbody>
	</table>
	<?php

	return ob_get_clean();
}
