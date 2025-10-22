<?php declare(strict_types=1);

/**
 * Utilities for parsing data from requests.
 */
namespace Req;

use Exception;

/**
 * Get the query string parameter with the given name.
 * If not given or blank, returns `null`.
 *
 * @param string $name
 * @return string|null
 */
function get(string $name): ?string
{
	$value = $_GET[$name] ?? '';
	$value = trim($value = $value);

	if ($value === '')
	{
		return null;
	}

	return $value;
}

/**
 * Get the POST-body request parameter with the given name.
 * If not given or blank, returns `null`.
 *
 * @param string $name
 * @return string|null
 */
function post(string $name): ?string
{
	$value = $_POST[$name] ?? '';
	$value = trim($value = $value);

	if ($value === '')
	{
		return null;
	}

	return $value;
}

/**
 * Very fancy form-parsing stuff which made the EOI processing a bit more pleasant to write.
 * 
 * Provides a wide array of requirements that can be enforced on the shape of an input dataset, and
 * allows you to map/deserialize that data.
 * 
 * @author ashlyn r
 */
class FormContext
{
	/**
	 * List of errors with the user's input data. If non-empty, the form data should be considered
	 * invalid. Formatted as HTML.
	 * 
	 * @var string[]
	 */
	public array $htmlErrorList = [];

	/**
	 * Create a form data context from the given associative array of user input data.
	 */
	function __construct(private array $inputArray)
	{}

	/**
	 * Create a form data context from the POST request body.
	 * @return static
	 */
	public static function fromPostBody(): static
	{
		return new static($_POST);
	}

	/**
	 * Whether any errors have been encountered so far in the input data.
	 */
	public function hasErrors(): bool
	{
		return count($this->htmlErrorList) > 0;
	}

	/**
	 * Retrieve a piece of data provided by the user in the form.
	 * 
	 * @param string $readableName Human-readable name of this form input
	 * @param string $key Internal data name of this input
	 * @param bool $required Whether this input must be provided, or should default to `null`
	 * @param ?string $regex An optional regular expression the data must pass to be valid
	 * @param ?int $filterMode An optional `FILTER_*` mode the data must match to be valid
	 * @param ?int $minLength Optional minimum string length of the data
	 * @param ?int $maxLength Optional maximum string length of the data
	 * @param ?callable $mapValue Optional function to map the data into another type, if all the above criteria match. May throw an `Exception` to indicate bad data
	 * @return mixed The data, or `null` if it failed to match. In the latter case, an error will be added to the error list
	 */
	public function input(
		string $readableName,
		string $key,
		bool $required,
		?string $regex = null,
		?int $filterMode = null,
		?int $minLength = null,
		?int $maxLength = null,
		?callable $mapValue = null
	): mixed
	{
		$value = $this->inputArray[$key] ?? null;

		try
		{
			return mapInputValue(
				value: $value,
				required: $required,
				regex: $regex,
				filterMode: $filterMode,
				minLength: $minLength,
				maxLength: $maxLength,
				mapValue: $mapValue
			);
		}
		catch (InputException $ex)
		{
			$error = 'Your ' . htmlspecialchars($readableName) . ' ' . $ex->messageSuffix();

			if (is_string($value))
			{
				$error .= ' You wrote: <q>' . htmlspecialchars(strval($value)) . '</q>';
			}

			$this->htmlErrorList[] = $error;

			return null;
		}
	}

	/**
	 * Retrieve an array of data provided by the user in the form.
	 * 
	 * @param string $readableName Human-readable name of this form input
	 * @param string $key Internal data name of this input
	 * @param bool $required Whether this input must be provided, or should default to `null`
	 * @param ?string $memberRegex An optional regular expression each member must pass to be valid
	 * @param ?int $memberFilterMode An optional `FILTER_*` mode each member must match to be valid
	 * @param ?int $memberMinLength Optional minimum string length of each member's data
	 * @param ?int $memberMaxLength Optional maximum string length of each member's data
	 * @param ?callable $mapMember Optional function to map each array member into another type, if all the above criteria match. May throw an `Exception` to indicate bad data
	 * @return ?array The data, or `null` if it failed to match. In the latter case, an error will be added to the error list
	 */
	public function inputArray(
		string $readableName,
		string $key,
		bool $required,
		?string $memberRegex = null,
		?int $memberFilterMode = null,
		?int $memberMinLength = null,
		?int $memberMaxLength = null,
		?callable $mapMember = null
	): ?array
	{
		return $this->input(
			readableName: $readableName,
			key: $key,
			required: $required,
			mapValue: function (mixed $array) use(
				$memberRegex,
				$memberFilterMode,
				$memberMinLength,
				$memberMaxLength,
				$mapMember
			): ?array
			{
				if (!is_array($array))
				{
					throw new InputMapFailedException('should be a list of items.');
				}

				$outArray = [];

				/** @var string[] */
				$htmlErrorList = [];

				foreach ($array as $member)
				{
					try
					{
						$outArray[] = mapInputValue(
							value: $member,
							required: false,
							regex: $memberRegex,
							filterMode: $memberFilterMode,
							minLength: $memberMinLength,
							maxLength: $memberMaxLength,
							mapValue: $mapMember
						);
					}
					catch (InputException $ex)
					{
						$htmlErrorList []= sprintf('<li><q>%s</q> %s</li>',
							htmlspecialchars($member),
							htmlspecialchars($ex->messageSuffix())
						);

						continue;
					}
				}
				
				if (count($htmlErrorList) > 0)
				{
					$errorListHtml = implode($htmlErrorList);

					throw new InputMapFailedException(sprintf(
						"has %u incorrect entries: <ul>%s</ul>",
						count($htmlErrorList),
						$errorListHtml
					));
				}

				return $outArray;
			}
		);
	}
}

/**
 * Map a user-given input value based on some number of criteria.
 * 
 * Used as the basis for FormContext.
 * 
 * @throws InputException If the criteria failed to match
 */
function mapInputValue(
	mixed $value,
	bool $required,
	?string $regex = null,
	?int $filterMode = null,
	?int $minLength = null,
	?int $maxLength = null,
	?callable $mapValue = null
): mixed
{
	if (is_string($value))
	{
		$value = trim($value);
	}

	if ($value === null || $value === '')
	{
		if ($required)
		{
			throw new InputRequiredException;
		}

		return null;
	}

	if ($filterMode !== null)
	{
		$value = filter_var($value, $filterMode);
	}

	if (is_string($value) && ($maxLength !== null || $minLength !== null))
	{
		$length = mb_strlen($value);

		if ($minLength !== null && ($length < $minLength))
		{
			throw new InputTooShortException($minLength);
		}

		if ($maxLength !== null && ($length > $maxLength))
		{
			throw new InputTooLongException($maxLength);
		}
	}

	if (($value === false) || ($regex !== null && preg_match($regex, $value) !== 1))
	{
		throw new InputBadFormatException;
	}

	if ($mapValue === null)
	{
		if (is_string($value))
		{
			return trim($value);
		}

		return $value;
	}

	try
	{
		return $mapValue($value);
	}
	catch (\Exception $inner)
	{
		if ($inner instanceof InputMapFailedException)
		{
			throw $inner;
		}

		throw new InputMapFailedException(previous: $inner);
	}
}

abstract class InputException extends Exception
{
	public abstract function messageSuffix(): string;
}

class InputRequiredException extends InputException
{
	#[\Override]
	public function messageSuffix(): string
	{
		return 'is required.';
	}
}

class InputBadFormatException extends InputException
{
	#[\Override]
	public function messageSuffix(): string
	{
		return 'TODO';
	}
}

class InputTooShortException extends InputException
{
	function __construct(public readonly int $minLength)
	{}

	#[\Override]
	public function messageSuffix(): string
	{
		return "must be at least {$this->minLength} letters long.";
	}
}

class InputTooLongException extends InputException
{
	function __construct(public readonly int $maxLength)
	{}

	#[\Override]
	public function messageSuffix(): string
	{
		return "must be shorter than {$this->maxLength} letters.";
	}
}

class InputMapFailedException extends InputException
{
	function __construct(
		public readonly ?string $messageSuffix = null,
		?\Throwable $previous = null
	)
	{
		parent::__construct(previous: $previous);
	}

	#[\Override]
	public function messageSuffix(): string
	{
		if ($this->messageSuffix !== null)
		{
			return $this->messageSuffix;
		}

		return 'doesn\'t look correct.';
	}
}
