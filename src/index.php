<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Juno Pittard">
	<meta name="description" content="Applied Web Project Part 1 - Index Page">
	<meta name="keywords" content="cybersecurity, company, watertightcybersec, salespitch">
	<title>Watertight CyberSec</title>
	<link rel="stylesheet" href="./css/styles.css">
</head>
<body>
	<?php include(__DIR__ . '/templates/navbar.html'); ?>

	<!--
		Optional full-screen content for the most important page content, if applicable.
		
		Page-specific styling may override the `background-image` of this element to a relevant
		image.
	-->
	<header id="hero-container">
		<div id="hero">
			<img src="images/logo.png" alt="Company Logo">
			<h1>Watertight Cyber Securities</h1>
			<!-- slogan -->
			<em>Out of sight, out of mind</em>
		</div>
	</header>

	<!-- Main content of the page. -->
	<main>
		<article id="content">
			<h2>Why choose us?</h2>

			<p>
				Watertight CyberSec takes the burden off you and puts it on us. Never worry about vulnerabilities again - that's what we're here for!
			</p>

			<p>
				We specialise in HTML and CSS code. We promise to deliver flawless programming that'll be so secure, you won't even have to check.
			</p>

			<p>
				In fact, we're so confident in our work that we'll manage any bug reports or data breaches ourselves, and only forward the critical, stock-plummeting news to you.
			</p>

			<p>
				Not that there will be any. <br>
				Because it's perfect. <br>
				You don't need to check.
			</p>

			<table>
				<caption>Here's how we're tracking</caption>

				<thead>
					<tr>
						<th>Security Vulnerabilities</th>
						<th>Projects Delivered</th>
						<th>CEOs made Happy</th>
						<th>CEOs made Unhappy</th>
						<!-- We're required to use inline CSS somewhere for some reason. Here is that! -->
						<th><a style="color: var(--col-text);" href="https://www.youtube.com/watch?v=fu3ETgAvQrw">Times Logo Redesigned</a></th>
						<th>Table Columns</th>
					</tr>
				</thead>

				<tbody>
					<tr>
						<td>0</td>
						<td>1</td>
						<td>0</td>
						<td>0</td>
						<td>4</td>
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

	<?php include(__DIR__ . '/templates/footer.html'); ?>
</body>
</html>
