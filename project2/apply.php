<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Aadil Vinod">
	<meta name="description" content="Apply to work with us!">
	<meta name="keywords" content="cybersecurity, company, watertightrecruitment, hiring, jobs">
	<title>Apply | Watertight Recruitment</title>
	<link rel="stylesheet" href="./css/styles.css">
	<link rel="stylesheet" href="./css/per-page/apply.css">
</head>
<body>
	<?php include(__DIR__ . '/header.inc'); ?>

	<!--
		Optional full-screen content for the most important page content, if applicable.
		
		Page-specific styling may override the `background-image` of this element to a relevant
		image.
	-->
	<header id="hero-container">
		<div id="hero">
			<h1>Apply for a Position</h1>
			<p>
				Our clients are always looking for new members who're passionate about Cyber Security. If you've checked our
				<a href="./jobs.php">Jobs Page</a> and think you'd be a good fit for one of our roles, apply today using
				the form below.
			</p>
		</div>
	</header>

	<!-- Main content of the page. -->
	<main>
		<form id="content" action="./process_eoi.php" method="post" autocomplete="on">
			<!-- Job reference number: exactly 5 alphanumeric -->
			<p>
				<label for="ref">Job reference number</label>
				<input id="ref" name="reference"
							type="text" inputmode="text" autocomplete="off"
							title="See the Jobs page for the reference number for your position."
							placeholder="e.g. J0123">
			</p>

			<!-- First name -->
			<p>
				<label for="fname">First name</label>
				<input id="fname" name="first_name"
							type="text" autocomplete="given-name"
							title="Letters plus space, hyphen, or apostrophe; up to 20 characters.">
			</p>

			<!-- Last name -->
			<p>
				<label for="lname">Last name</label>
				<input id="lname" name="last_name"
							type="text" autocomplete="family-name"
							title="Letters plus space, hyphen, or apostrophe; up to 20 characters.">
			</p>

			<!-- DOB: dd/mm/yyyy -->
			<p>
				<label for="dob">Date of birth</label>
				<input id="dob" name="dob"
							type="text" inputmode="numeric" placeholder="dd/mm/yyyy"
							title="Use format dd/mm/yyyy, e.g. 07/02/1998">
			</p>

			<!-- Gender -->
			<fieldset>
				<legend>Gender</legend>
				<label><input type="radio" name="gender" value="female"> Female</label>
				<label><input type="radio" name="gender" value="male"> Male</label>
				<label><input type="radio" name="gender" value="nonbinary"> Non-binary</label>
				<label><input type="radio" name="gender" value="prefer_not"> Prefer not to say</label>
			</fieldset>

			<!-- Street Address -->
			<p>
				<label for="street">Street Address</label>
				<input id="street" name="street_address"
							type="text" autocomplete="address-line1"
							title="Up to 40 characters.">
			</p>

			<!-- Suburb/Town -->
			<p>
				<label for="suburb">Suburb/Town</label>
				<input id="suburb" name="suburb"
							type="text" autocomplete="address-level2"
							title="Letters, spaces, hyphens, apostrophes; up to 40 characters.">
			</p>

			<!-- State -->
			<p>
				<label for="state">State</label>
				<select id="state" name="state" autocomplete="address-level1">
					<option value="">-- Select --</option>
					<option>VIC</option>
					<option>NSW</option>
					<option>QLD</option>
					<option>NT</option>
					<option>WA</option>
					<option>SA</option>
					<option>TAS</option>
					<option>ACT</option>
				</select>
			</p>

			<!-- Postcode -->
			<p>
				<label for="postcode">Postcode</label>
				<input id="postcode" name="postcode"
							type="text" inputmode="numeric" autocomplete="postal-code"
							title="Exactly 4 digits, e.g. 3000">
			</p>

			<!-- Email -->
			<p>
				<label for="email">Email</label>
				<input id="email" name="email"
							type="email" autocomplete="email"
							placeholder="name@example.com">
			</p>

			<!-- Phone -->
			<p>
				<label for="phone">Phone number</label>
				<input id="phone" name="phone"
							type="text" inputmode="tel" autocomplete="tel-national"
							title="8-12 digits, numbers only.">
			</p>

			<!-- Skill list -->
			<fieldset>
				<legend>Skill list (select at least one)</legend>

				<!--
				Ashlyn, 24/09/2025:

				Pure HTML Forms don't give us the option to require *at least one* checkbox. We'll revisit this with
				JS in Part 2. For now, we'll just pretend that the server validates the selection correctly.
				-->
				<div class="checkbox-list multiline">
					<label><input type="checkbox" name="skills[]" value="soc_siem"> SOC monitoring / SIEM (e.g., Splunk)</label>
					<label><input type="checkbox" name="skills[]" value="incident_response"> Incident response &amp; triage</label>
					<label><input type="checkbox" name="skills[]" value="vuln_mgmt"> Vulnerability management</label>
					<label><input type="checkbox" name="skills[]" value="cloud_security"> Cloud security (AWS/Azure/GCP)</label>
					<label><input type="checkbox" name="skills[]" value="iam_mfa"> IAM, RBAC &amp; MFA administration</label>
					<label><input type="checkbox" name="skills[]" value="network_security"> Network security &amp; firewalls</label>
					<label><input type="checkbox" name="skills[]" value="scripting"> Scripting/Automation (Python/Bash)</label>
					<label><input type="checkbox" id="skill_other" name="skills[]" value="other"> Other skills...</label>
				</div>

				<p id="other-hint">Selecting “Other skills...” is optional; you can describe them below.</p>
			</fieldset>

			<!-- Other skills -->
			<p>
				<label for="otherSkills">Other skills</label>
				<textarea id="otherSkills" name="other_skills" rows="4" cols="40"
					aria-describedby="other-hint"
					placeholder="e.g., GRC, ISO 27001, threat hunting, DFIR tools… (optional)"></textarea>
			</p>

			<!-- Actions -->
			<p class="action-buttons">
				<button type="reset">Reset</button>
				<button type="submit">Submit application</button>
			</p>
		</form>
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
