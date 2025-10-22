<?php 

function defaultEOI($db) {
	echo "function incomplete";
}

function defaultJobs($db) {
	// populate jobs table with default data
	$db->execute_query("INSERT INTO jobs (ref, title, salary_low, salary_high, reporting_line, about) 
	VALUES ('J0115', 'Security Analyst', 80000, 100000, 'Analyst Team Lead',
	'As a security analyst, you will work with our IT team to analyse and respond to emerging and existing threats. You will be tasked with monitoring our existing clients'' projects and ensuring security. We ask you to keep up with modern and emerging attack vectors and potential vulnerabilities to pre-empt attacks.'),
	('J0201', 'Incident Response Specialist', 120000, 160000, 'Incident Team Coordinator',
	'As an Incident Response Specialist, you will join our threat strike team and work on mitigating time-critical active threats. You will work with our analysts and clients to respond to cyberattacks as they happen, and assist in recovery afterwards. When there are no current incidents, you will be expected to proactively seek out new or advanced threats, which may evade regular detection.'),
	('J0302', 'Secure Culture Coordinator', 100000, 120000, 'Chief Communications Officer',
	'As our Secure Culture Coordinator, you will work with our clients to ensure that their organisation has good individual-level cybersecurity practices. You will be responsible for coordinating outreach, and ensuring that every employee at the clients'' business is aware of common attack vectors like phishing. ');
	");

	// populate essential requirements table with default data
	$db->execute_query("INSERT INTO jobs_ess_reqs (jobs_ref, ess_text)
	VALUES ('J0115', 'A relevant degree, or equivalent certification or training'),
	('J0115', 'Strong previous experience with cybersecurity and project maintenance'),
	('J0115', 'Knowledge of a range of languages and computer systems'),
	('J0201', 'Consistent and strong history of field-based cybersecurity response'),
	('J0201', 'Deep understanding of current, deprecated, and potential attack vectors'),
	('J0201', 'Availability to be on-call for emergencies'),
	('J0302', 'Understanding of common cyber attacks and how to avoid vulnerability'),
	('J0302', 'People skills and an enthusiasm for communication'),
	('J0302', 'Demonstrated history of coordination or outreach measures');
	");

	// populate preferred requirements table with default data
	$db->execute_query("INSERT INTO jobs_pref_reqs (jobs_ref, pref_text)
		VALUES ('J0115', 'Problem solving skills and an analytical focus'),
	('J0115', 'Understanding of legal security requirements'),
	('J0201', 'Industry certifications in cybersecurity'),
	('J0201', 'Skills in automation and event detection'),
	('J0302', 'A relevant degree in either cybersecurity or human resources'),
	('J0302', 'Knowledge of attack simulations and their ethical use');
	");
}

