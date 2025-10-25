<?php declare(strict_types=1);

namespace Templates;

/**
 * A dropdown of multiple choices.
 *
 * @param string $readableName Human-readable name of the input for the label
 * @param string $key Internal name for the input when sent in the form data
 * @param (array<string, string>) $options Associative array of option names to their values
 * @param ?string $initialChoiceName An initially selected option, if specified
 * @return string The HTML for the input
 */
function selectInput(
	string $readableName,
	string $key,
	array $options,
	?string $initialChoiceName = null,
): string
{
	ob_start();

	$sanitizedKey = htmlspecialchars($key, ENT_QUOTES);

	?>
		<label for="select-<?= $sanitizedKey ?>">
			<?= htmlspecialchars($readableName) ?>
		</label>

		<select name="<?= $sanitizedKey ?>" id="select-<?= $sanitizedKey ?>">
			<?php foreach ($options as $optionName => $optionValue): ?>
				<option
					value="<?= htmlspecialchars($optionValue, ENT_QUOTES) ?>"
					<?php if ($optionName === $initialChoiceName): ?>
						selected
					<?php endif ?>
				>
					<?= htmlspecialchars($optionName) ?>
				</option>
			<?php endforeach ?>
		</select>
	<?php

	return ob_get_clean();
}
