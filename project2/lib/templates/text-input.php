<?php declare(strict_types=1);

namespace Templates;

/**
 * A textual form input with a label.
 *
 * @param string $readableName Human-readable name of the input for the label
 * @param string $key Internal name for the input when sent in the form data
 * @param bool $required Whether this input is required to submit the form
 * @param ?string $initialValue An initial value held for the input, if any
 * @return string The HTML for the input
 */
function textInput(
	string $readableName,
	string $key,
	bool $required,
	?string $initialValue = null,
): string
{
	ob_start();

	$sanitizedKey = htmlspecialchars($key, ENT_QUOTES);

	?>
		<label for="input-<?= $sanitizedKey ?>">
			<?= htmlspecialchars($readableName) ?>
		</label>

		<input
			type="text"
			name="<?= $sanitizedKey ?>"
			id="input-<?= $sanitizedKey ?>"
			
			<?php if ($required): ?>
				required
			<?php endif ?>

			<?php if ($initialValue !== null): ?>
				value="<?= htmlspecialchars($initialValue, ENT_QUOTES) ?>"
			<?php endif ?>
		>
	<?php

	return ob_get_clean();
}
