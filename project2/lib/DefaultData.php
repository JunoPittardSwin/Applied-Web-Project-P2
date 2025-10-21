<?php 

function defaultContributions($db) {
	//populate contributions table with default data
	$db->execute_query("INSERT INTO contributions (id, team_member, contribution_text)
	VALUES ('NULL', 'Juno', 'About page, static and dynamic rendering'),
	('NULL', 'Juno', 'Jobs page, static and dynamic rendering'),
	('NULL', 'Juno', 'Index page, static HTML'),
	('NULL', 'Juno', 'Content writing'),
	('NULL', 'Ashlyn', 'Site design'),
	('NULL', 'Ashlyn', 'CSS styling'),
	('NULL', 'Ashlyn', 'Team coordination and management'),
	('NULL', 'Ashlyn', 'Management page, login, and user queries'),
	('NULL', 'Aadil', 'Application page, static HTML'),
	('NULL', 'Aadil', 'Application form with MySQL integration'),
	('NULL', 'Aadil', 'EOI processing checks');
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
