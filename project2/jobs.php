<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Juno Pittard">
	<meta name="description" content="Applied Web Project Part 1 - Jobs Page">
	<meta name="keywords" content="cybersecurity, jobs, hiring">
	<title>Open Jobs | Watertight CyberSec</title>
	<link rel="stylesheet" href="./css/styles.css">
	<link rel="stylesheet" href="./css/per-page/jobs.css">
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
			<h1>We're Hiring!</h1>
			<p>
				See our currently open positions below
			</p>
		</div>
	</header>

	<!-- Main content of the page. -->
	<main>
		<!-- theoretical reference number format: Job, Internal=0 Contractor=1, 1 digit for Team ID, 2 digits for team position.  -->
		<article id="content">
			<section>
				<h2>Security Analyst (REF:J0115)</h2>
				<em>Salary: $80,000 - $100,000 p/a <br>
				Reporting Line: Analyst Team Lead
				</em>

				<h3>About the role</h3>
				<p>
					As a security analyst, you will work with our IT team to analyse and respond to emerging and existing threats.
					You will be tasked with monitoring our existing clients' projects and ensuring security.
					We ask you to keep up with modern and emerging attack vectors and potential vulnerabilities to pre-empt attacks.
				</p>

				<h3>Key responsibilities:</h3>
				<ul>
					<li>Monitor security events and logs</li>
					<li>Mitigate and respond to existing threats</li>
					<li>Conduct regular vulnerability assessments and penetration tests</li>
					<li>Maintain and manage firewall and policy software</li>
					<li>Collaborate with the client to keep them updated on their security</li>
				</ul>

				<!-- yes, unfortunately, these do have to be ordered lists -->
				<h3>Essential requirements:</h3>
				<ol>
					<li>A relevant degree, or equivalent certification or training</li>
					<li>Strong previous experience with cybersecurity and project maintenance</li>
					<li>Knowledge of a range of languages and computer systems</li>
				</ol>

				<h3>Preferred requirements:</h3>
				<ol>
					<li>Problem solving skills and an analytical focus</li>
					<li>Understanding of legal security requirements</li>
				</ol>

				<a class="button" href="./apply.php">Apply for J0115</a>
			</section>

			<section>
				<h2>Incident Response Specialist (REF:J0201)</h2>

				<aside>
					<h3>Strike Teams</h3>
					<p>If you wish to be considered for our strike teams that require security clearance, please <a href="mailto:hiring@watertightcybersec.com">contact us</a></p>
				</aside>

				<em>Salary: $120,000 - $160,000 p/a <br>
				Reporting Line: Incident Team Coordinator and client contact
				</em>

				<h3>About the role</h3>
				<p>
					As an Incident Response Specialist, you will join our threat strike team and work on mitigating time-critical active threats.
					You will work with our analysts and clients to respond to cyberattacks as they happen, and assist in recovery afterwards.
					When there are no current incidents, you will be expected to proactively seek out new or advanced threats, which may evade regular detection.
				</p>

				<h3>Key responsibilities:</h3>
				<ul>
					<li>Be on call for emergency cyberattack response</li>
					<li>Collaborate with the strike team to increase effectivity</li>
					<li>Stay up to date on emerging threats and techniques that may bypass existing security measures</li>
					<li>Conduct codebase and server reviews to detect existing threats</li>
				</ul>

				<h3>Essential requirements:</h3>
				<ol>
					<li>Consistent and strong history of field-based cybersecurity response</li>
					<li>Deep understanding of current, deprecated, and potential attack vectors</li>
					<li>Availability to be on-call for emergencies</li>
				</ol>

				<h3>Preferred requirements:</h3>
				<ol>
					<li>Industry certifications in cybersecurity</li>
					<li>Skills in automation and event detection</li>
				</ol>

				<a class="button" href="./apply.php">Apply for J0201</a>
			</section>

			<section>
				<h2>Secure Culture Coordinator (REF: J0302)</h2>
				<em>Salary: $100,000 - $120,000 p/a <br>
				Reporting Line: Chief Communications Officer
				</em>

				<h3>About the role</h3>
				<p>
					As our Secure Culture Coordinator, you will work with our clients to ensure that their organisation has good individual-level cybersecurity practices.
					You will be responsible for coordinating outreach, and ensuring that every employee at the client's business is aware of common attack vectors like phishing.
				</p>

				<h3>Key responsibilities:</h3>
				<ul>
					<li>Organise seminars and newsletters to inform employees</li>
					<li>Conduct phishing and other attack simulations to locate areas to improve</li>
					<li>Check in with clients and their workers regularly about their concerns and knowledge</li>
				</ul>

				<h3>Essential requirements:</h3>
				<ol>
					<li>Understanding of common cyber attacks and how to avoid vulnerability</li>
					<li>People skills and an enthusiasm for communication</li>
					<li>Demonstrated history of coordination or outreach measures</li>
				</ol>

				<h3>Preferred requirements:</h3>
				<ol>
					<li>A relevant degree in either cybersecurity or human resources</li>
					<li>Knowledge of attack simulations and their ethical use</li>
				</ol>
				
				<a class="button" href="./apply.php">Apply for J0302</a>
			</section>
		</article>
	</main>

	<?php include(__DIR__ . '/templates/footer.html'); ?>
</body>
</html>
