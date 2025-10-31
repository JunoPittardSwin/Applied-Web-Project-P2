<?php

/*
filename: about.php
author: Juno Pittard
created: 23/08/2025
last modified: 26/10/2025
description: Information about the team. Loads the team list and contributions from the database.
*/

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Juno Pittard">
	<meta name="description" content="Applied Web Project Part 1 - About Page">
	<meta name="keywords" content="cybersecurity, company, watertightrecruitment, team, about, information">
	<title>About Us | Watertight Recruitment</title>
	<link rel="stylesheet" href="./styles/style.css">
</head>
<body>
	<?php require_once(__DIR__ . '/settings.php');
	include(__DIR__ . '/lib/DefaultData.php');
	include(__DIR__ . '/header.inc');

	// if the team_members table is empty, populate it
	$contrib = $db->execute_query("SELECT * FROM team_members;");
	if ($contrib->num_rows == 0) {
		defaultContributions($db);
	}
	$contrib->close();
	?>
	

	<header id="hero-container" class="hero-background-team">
		<div id="hero">
			<h1>About Us</h1>
			<!-- assignment requires that these are nested lists -->
			<ul>
				<li>
					We're the Watertight Recruitment Team!
					<ul>
						<li>Working hours: 2:30 to 4:30 in EN302</li>
					</ul>
				</li>
			</ul>
		</div>
	</header>

	<main>
		<article id="content">
			<h2>Our team:</h2>

			<div id="team-photo-and-list">
				<!-- team photo in a figure element -->
				<figure id="team-photo">
					<a href="./images/group-photo.jpg">
						<img src="./images/group-photo.jpg" alt="Team Photo">
					</a>
					<figcaption>Left to Right: Juno, Ashlyn, Aadil</figcaption>
				</figure>

				<!-- nested list of member details, making use of definition lists -->
				<ol id="team-list">
					<li>
						<?php
							// cr: Ashlyn's code from PR, tweaked to fit
							$result = $db->query('SELECT * FROM team_members');
							while (true) {
							
								$row = $result->fetch_assoc();
								if (!is_array($row))
								{
									break;
								}
								$teamMemberId = $row['student_id'];
								?>
								<h3><?= htmlspecialchars($row['name']) ?> <span class="student-id">(<?= strval($row['student_id']) ?>)</span></h3>
								<dl>
									<dt>Contributions</dt>
									<dd><ol>
										<?php
											$contribsResult = $db->execute_query(
												"SELECT contribution_text FROM contributions
												WHERE team_member_id = ?", [$row['student_id']]
											);

											$contributions = [];

											while (true)
											{
												$contribution = $contribsResult->fetch_column();

												if (!is_string($contribution))
												{
													break;
												}
												$contributions []= $contribution;
											}

											foreach($contributions as $c) {
												echo "<li>" . htmlspecialchars($c) . "</li>";
											}

											$contribsResult->close();
										?>
									</ol></dd>
									<dt>Favourite Quote</dt>
									<dd><?= htmlspecialchars($row['quote']) ?></dd>
									<dt>Favourite Language</dt>
									<dd><?= htmlspecialchars($row['language'])?></dd>
									<dt>Translation</dt>
									<dd><?= htmlspecialchars($row['translation'])?></dd>
								</dl>
							<?php
							}

							$result->close();
						?>
					</li>
				</ol>
			</div>

			<table>
				<caption>Fun Facts</caption>

				<thead>
					<tr>
						<th>Team Member</th>
						<th>Dream Job</th>
						<th>Favourite Snack</th>
						<th>Hometown</th>
						<th>Favourite Study Spot</th>
						<th>Favourite HTML Element</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						$result = $db->query('SELECT * FROM team_members');
						while (true) {
							$row = $result->fetch_assoc();
							if (!is_array($row))
							{
								break;
							} 
							?>
							<tr>
								<td><?= htmlspecialchars($row['name'])?></td>
								<td><?= htmlspecialchars($row['job']) ?></td>
								<td><?= htmlspecialchars($row['snack'])?></td>
								<td><?= htmlspecialchars($row['town'])?></td>
								<td><?= htmlspecialchars($row['study'])?></td>
								<!-- below block checks for Ashlyn's ID, and applies the unique styling if so. Otherwise, display normally. -->
								<?php
								if(htmlspecialchars($row['student_id']) == 105928880) {?>
									<td class="fake-marquee-container">
										<div class="fake-marquee-y">
											<div class="fake-marquee-x">
												<?= htmlspecialchars($row['element'])?>
											</div>
										</div>
									</td>
									<?php } else {
										?><td><?= htmlspecialchars($row['element'])?></td>
										<?php
									}
								?>
							</tr>
							<?php
						}
					$result->close();
					?>
				</tbody>
			</table>
		</article>
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
