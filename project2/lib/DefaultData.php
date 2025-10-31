<?php 

/*
filename: view-eoi.php
author: Juno Pittard
created: 20/10/2025
last modified: 27/10/2025
description: Automatic sample data insertion to the database on first site visit.
*/

function defaultContributions($db) {

	// populate team_members table with default data
	$db->execute_query("INSERT INTO team_members (student_id, name, quote, language, translation, job, snack, town, study, element)
	VALUES (103983984, 'Juno', 'Keep on keeping on!', 'French', 'Continue comme ça!', 'Game Developer', 'Soy sauce fish', 'Camberwell', 'Hawthorn library', '<a>'),
	(105928880, 'Ashlyn', 'death by tray it shall be', 'Old Norse', 'ᛒᚨᚾᚨᛞᚨᚢᚦᛁ ᚨᚠ ᛒᚨᚲᚨ ᛊᚲᚨᛚ ᚦᚨᛏ ᚢᛖᚱᚨ', 'Reverse Engineering / Software Development', 'Dumplings or Gnocchi', 'Sassafras', 'Latelab, floor 3', '<marquee>'),
	(105700716, 'Aadil', 'Not all those who wander are lost', 'Malayalam', 'മലയാളം ആണ് എന്റെ ഇഷ്ട ഭാഷ.', 'Software Engineer', 'Biryani', 'Laverton', 'On campus', 'Void elements');
	");

	// populate contributions table with default data
	$db->execute_query("INSERT INTO contributions (team_member_id, contribution_text)
	VALUES (103983984, 'About, Jobs and Index pages, static rendering'),
	(103983984, 'About page dynamic rendering'),
	(103983984, 'Content writing'),
	(103983984, 'Database structure, about page tables auto-generation'),
	(105928880, 'Site design and CSS styling'),
	(105928880, 'Team coordination and management'),
	(105928880, 'GitHub oversight and pull request reviews'),
	(105928880, 'Management page, login, and user queries'),
	(105700716, 'Application page, static rendering'),
	(105700716, 'Application form and page integration'),
	(105700716, 'Project part 1 to 2 consolidation');
	");
}

function defaultJobs(JobManager $jobManager)
{
	$jobManager->createJobListing(new JobListing(
		ref: 'J0115',
		title: 'Security Analyst',
		salaryLowBracket: 80000,
		salaryHighBracket: 100000,
		reportingLine: 'Analyst Team Lead',
		aboutHtml: "
			As a security analyst, you will work with our IT team to analyse and respond to emerging
			and existing threats. You will be tasked with monitoring our existing clients' projects
			and ensuring security. We ask you to keep up with modern and emerging attack vectors and
			potential vulnerabilities to pre-empt attacks.
		",
		essentialRequirements: [
			'A relevant degree, or equivalent certification or training',
			'Strong previous experience with cybersecurity and project maintenance',
			'Knowledge of a range of languages and computer systems',
		],
		preferredRequirements: [
			'Problem solving skills and an analytical focus',
			'Understanding of legal security requirements',
		]
	));

	$jobManager->createJobListing(new JobListing(
		ref: 'J0201',
		title: 'Incident Response Specialist',
		salaryLowBracket: 120000,
		salaryHighBracket: 160000,
		reportingLine: 'Incident Team Coordinator',
		aboutHtml: "
			As an Incident Response Specialist, you will join our threat strike team and work on
			mitigating time-critical active threats. You will work with our analysts and clients to
			respond to cyberattacks as they happen, and assist in recovery afterwards. When there
			are no current incidents, you will be expected to proactively seek out new or advanced
			threats, which may evade regular detection.
		",
		asideInfoHtml: "
			<h3>Strike Teams</h3>
			<p>
				If you wish to be considered for our strike teams that require security clearance,
				please <a href=\"mailto:hiring@watertightcybersec.com\">contact us</a>
			</p>
		",
		essentialRequirements: [
			'Consistent and strong history of field-based cybersecurity response',
			'Deep understanding of current, deprecated, and potential attack vectors',
			'Availability to be on-call for emergencies',
		],
		preferredRequirements: [
			'Industry certifications in cybersecurity',
			'Skills in automation and event detection',
		]
	));

	$jobManager->createJobListing(new JobListing(
		ref: 'J0302',
		title: 'Secure Culture Coordinator',
		salaryLowBracket: 100000,
		salaryHighBracket: 120000,
		reportingLine: 'Chief Communications Officer',
		aboutHtml: "
			As our Secure Culture Coordinator, you will work with our clients to ensure that their
			organisation has good individual-level cybersecurity practices. You will be responsible
			for coordinating outreach, and ensuring that every employee at the clients'' business
			is aware of common attack vectors like phishing.
		",
		essentialRequirements: [
			'Understanding of common cyber attacks and how to avoid vulnerability',
			'People skills and an enthusiasm for communication',
			'Demonstrated history of coordination or outreach measures',
		],
		preferredRequirements: [
			'A relevant degree in either cybersecurity or human resources',
			'Knowledge of attack simulations and their ethical use',
		]
	));
}
