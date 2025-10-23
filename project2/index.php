<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Juno Pittard">
	<meta name="description" content="Applied Web Project Part 1 - Index Page">
	<meta name="keywords" content="cybersecurity, company, watertightrecruitment, salespitch">
	<title>Watertight Recruitment</title>
	<link rel="stylesheet" href="./styles/styles.css">

</head>
<body>
	<?php include(__DIR__ . '/header.inc'); ?>

	<!--
		Optional full-screen content for the most important page content, if applicable.
		
		Page-specific styling may override the `background-image` of this element to a relevant
		image.
	-->
	<header class="hero-container">
		<div id="hero">
			<img src="./images/logo.png" alt="Company Logo">
			<h1>Watertight Recruitment</h1>
			<!-- slogan -->
			<em>Out of sight, out of mind</em>
		</div>
	</header>

	<!-- Main content of the page. -->
	<main>
		<article id="content">
			<h2>Why choose us?</h2>

			<p>
				Watertight Recruitment takes the burden off you and puts it on us. Never worry about vulnerabilities again - that's what we're here for!
			</p>

			<p>
				We hire specialists in cybersecurity and web development. We promise to deliver flawless programmers that'll be so secure, you won't even have to check.
			</p>

			<table>
				<caption>Here's how we're tracking</caption>

				<thead>
					<tr>
						<th>Applicants hired</th>
						<th>Applicants promoted</th>
						<th>CEOs made Happy</th>
						<th>CEOs made Unhappy</th>
						<th><a id="table-link" href="https://www.youtube.com/watch?v=fu3ETgAvQrw">Times Logo Redesigned</a></th>
						<th>Table Columns</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>0</td>
						<td>1</td>
						<td>0</td>
						<td>0</td>
						<td>5</td>
						<td>6</td>
					</tr>
				</tbody>
			</table>
			
			<p>
				This table updates in real time*!<br>
				<sup>*whenever we get around to updating it.</sup>
			</p>
		</article>
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
