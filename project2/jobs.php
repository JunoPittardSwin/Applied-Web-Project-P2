<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Juno Pittard">
	<meta name="description" content="Applied Web Project Part 1 - Jobs Page">
	<meta name="keywords" content="cybersecurity, jobs, hiring">
	<title>Open Jobs | Watertight Recruitment</title>
	<link rel="stylesheet" href="./css/styles.css">
	<link rel="stylesheet" href="./css/per-page/jobs.css">
</head>
<body>
	<?php require_once(__DIR__ . '/settings.php');
	include(__DIR__ . '/lib/DefaultData.php');
	include(__DIR__ . '/header.inc');

	$jobs = $db->execute_query("SELECT * FROM jobs;");
	if (mysqli_num_rows($jobs) == 0) {
		defaultJobs($db);
	}
	?>

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
				<?php
				$conn = mysqli_connect($DB_HOST,$DB_USER,$DB_PASSWORD,$DB_NAME);
				if($conn) {
					$result = mysqli_query($conn, "SELECT * FROM jobs;");
					if (mysqli_num_rows($result) > 0) {
						for($i = 1; $i <= mysqli_num_rows($result); $i++) {
							$row = mysqli_fetch_assoc($result);
							echo "<h2>" . $row['title'] . " (REF:" . $row['ref'] .")</h2>\n";
							echo "<em>Salary: $" . $row['salary_low'] . " - $" . $row['salary_high'] . " p/a <br>";
							echo "Reporting Line: " . $row['reporting_line'] . "</em>\n";
							echo "<h3>About the role</h3>\n";
							echo "<p>" . $row['about'] . "</p>\n";
							echo "<h3>Essential requirements:</h3>\n<ol>\n";
							$ref_num = $row['ref'];
							$ess_reqs = mysqli_query($conn, "SELECT * FROM jobs_ess_reqs WHERE jobs_ref = '$ref_num';");
							for($j = 1; $j <= mysqli_num_rows($ess_reqs); $j++) {
								$reqs_row = mysqli_fetch_assoc($ess_reqs);
								echo "<li>" . $reqs_row['ess_text'] . "</li>\n";
							}
							echo "</ol>\n";
							echo "<h3>Preferred requirements:</h3>\n<ol>\n";
							$pref_reqs = mysqli_query($conn, "SELECT * FROM jobs_pref_reqs WHERE jobs_ref = '$ref_num';");
							for($j = 1; $j <= mysqli_num_rows($pref_reqs); $j++) {
								$reqs_row = mysqli_fetch_assoc($pref_reqs);
								echo "<li>" . $reqs_row['pref_text'] . "</li>\n";
							}
							echo "</ol>\n";
							echo "<a class='button' href='./apply.php?reference=" . htmlspecialchars($row['ref'], ENT_QUOTES) . "'>Apply for " . $row['ref'] . "</a>";
						}
					} else {
						echo "<p>No jobs posted at this time. Check back later!</p>\n";
					}
				} else {
					echo "<p>Connection failed!</p>\n";
				}
				?>
			</section>
		</article>
	</main>

	<?php include(__DIR__ . '/footer.inc'); ?>
</body>
</html>
			