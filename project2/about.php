<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Juno Pittard">
	<meta name="description" content="Applied Web Project Part 1 - About Page">
	<meta name="keywords" content="cybersecurity, company, watertightrecruitment, team, about, information">
	<title>About Us | Watertight Recruitment</title>
	<link rel="stylesheet" href="./styles/styles.css">
</head>
<body>
	<?php include(__DIR__ . '/header.inc'); ?>

	<header class="hero-container" id="about-container">
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
						<h3>Juno Pittard <span class="student-id">(ID: 103983984)</span></h3>
						<dl>
							<dt>Contributions</dt>
							<dd>About, Home and Jobs pages, coordination with stakeholders</dd>
							<dt>Favourite Quote</dt>
							<dd><q>Keep on keeping on!</q></dd>
							<dt>Favourite Language</dt>
							<dd>French</dd>
							<dt>Translation</dt>
							<dd>"Continue comme ça!"</dd>
						</dl>
					</li>

					<li>
						<h3>Ashlyn Randall <span class="student-id">(ID: 105928880)</span></h3>
						<dl>
							<dt>Contributions</dt>
							<dd>Site design and CSS, team management</dd>
							<dt>Favourite Quote</dt>
							<dd><q>death by tray it shall be</q></dd>
							<dt>Favourite Language</dt>
							<dd>Old Norse</dd>
							<dt>Translation</dt>
							<dd>ᛒᚨᚾᚨᛞᚨᚢᚦᛁ ᚨᚠ ᛒᚨᚲᚨ ᛊᚲᚨᛚ ᚦᚨᛏ ᚢᛖᚱᚨ</dd>
						</dl>
					</li>

					<li>
						<h3>Aadil Vinod <span class="student-id">(ID: 105700716)</span></h3>
						<dl>
							<dt>Contributions</dt>
							<dd>Application page and application form</dd>
							<dt>Favourite Quote</dt>
							<dd><q>Not all those who wander are lost</q><dd>
							<dt>Favourite Language</dt>
							<dd>Malayalam</dd>
							<dt>Translation</dt>
							<dd>മലയാളം ആണ് എന്റെ ഇഷ്ട ഭാഷ.</dd>
						</dl>
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
					<tr>
						<td>Juno</td>
						<td>Game Developer</td>
						<td>Soy sauce fish</td>
						<td>Camberwell</td>
						<td>Hawthorn library</td>
						<td>&lt;a&gt;</td>
					</tr>

					<tr>
						<td>Ashlyn</td>
						<td>Reverse Engineering / Software Development</td>
						<td>Dumplings or Gnocchi</td>
						<td>Sassafras</td>
						<td>Latelab, floor 3</td>
						<td class="fake-marquee-container">
							<div class="fake-marquee-y">
								<div class="fake-marquee-x">
									&lt;marquee&gt;
								</div>
							</div>
						</td>
					</tr>

					<tr>
						<td>Aadil</td>
						<td>Software Engineer</td>
						<td>Biryani</td>
						<td>Laverton</td>
						<td>On campus</td>
						<td>Void elements</td>
					</tr>
				</tbody>
			</table>
		</article>
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
