<?php declare(strict_types=1);

/*
filename: document.php
author: Ashlyn Randall
created: 23/10/2025
last modified: 25/10/2025
description: HTML template for a page on the website. In the future, should be rolled out such that
the normal pages use it too. Allows us to make changes in one place, and reflect them everywhere.
*/

namespace Templates;

/**
 * Base page template to be used across the site.
 *
 * @param string|null $title Title of the page, escaped automatically
 * @param string $author Author of the page content
 * @param string|null $description Description of the page content
 * @param string|null $keywords Keywords about the page (this is technically obsolete and no search engines use this for anything anymore but we're expected to have them anyway)
 * @param (callable(): string) $mainContent Function that returns the main page content
 * @param (callable(): string)|null $heroContent Function that returns the hero element content, if any
 * @param (callable(): string)|null $additionalHeadContent Function that returns any additional `<head>` content, if any
 * @return string The HTML document, ready to display
 */
function document(
	?string $title,
	callable $mainContent,
	string $author = 'Watertight Team',
	?string $description = null,
	?string $keywords = null,
	?callable $heroContent = null,
	?callable $additionalHeadContent = null
): string
{
	ob_start();

	?>
	<!DOCTYPE html>
	<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="author" content="<?= htmlspecialchars($author, ENT_QUOTES) ?>">

		<?php if ($keywords !== null): ?>
			<meta name="description" content="<?= htmlspecialchars($description, ENT_QUOTES) ?>">
		<?php endif ?>
		
		<?php if ($keywords !== null): ?>
			<meta name="keywords" content="<?= htmlspecialchars($keywords, ENT_QUOTES) ?>">
		<?php endif ?>

		<title>
			<?php
			if ($title !== null)
			{
				echo htmlspecialchars($title) . '| Watertight Recruitment';
			}
			else
			{
				echo 'Watertight Recruitment';
			}
			?>
		</title>

		<link rel="stylesheet" href="./styles/style.css">

		<?php
		if ($additionalHeadContent !== null)
		{
			echo $additionalHeadContent();
		}
		?>
	</head>
	<body>
		<?php include(__DIR__ . '/../../header.inc'); ?>

		<?php if ($heroContent !== null): ?>
			<!--
				Optional full-screen content for the most important page content, if applicable.
				
				Page-specific styling may override the `background-image` of this element to a relevant
				image.
			-->
			<header id="hero-container">
				<div id="hero">
					<?= $heroContent() ?>
				</div>
			</header>
		<?php endif ?>

		<!-- Main content of the page. -->
		<main>
			<?= $mainContent() ?>
		</main>

		<?php include(__DIR__ . '/../../footer.inc'); ?>
	</body>
	</html>

	<?php

	return ob_get_clean();
}
