<?php declare(strict_types=1);

class EoiManager
{
	function __construct(private mysqli $db)
	{
		$this->db->query("CREATE TABLE IF NOT EXISTS eoi(
			id INTEGER PRIMARY KEY AUTO_INCREMENT,

			-- ID of the job that's being applied for
			jobReferenceId CHAR(5) NOT NULL,

			-- The current status of this application in the review process.
			status ENUM('New', 'Current', 'Final') NOT NULL DEFAULT('New'),

			firstName VARCHAR(32) NOT NULL,
			lastName VARCHAR(32) NOT NULL,
			emailAddress VARCHAR(64) NOT NULL,

			-- https://en.wikipedia.org/wiki/Telephone_numbers_in_Australia
			-- 
			-- This is a string rather than a number, because people are very bad at typing phone numbers
			-- consistently, and there's a lot of variability no matter what. We aren't auto-dialling
			-- applicants, and our DB shouldn't have to care how they're written out.
			phoneNumber VARCHAR(20) NOT NULL,

			-- Optional field, applicant may choose not to specify.
			gender VARCHAR(16),

			-- Optional field, applicant may choose not to specify.
			dateOfBirth DATE,

			-- The state the applicant currently lives in. Non-nullable as we need this information for
			-- remote/local work decisions & filtering.
			state ENUM('VIC', 'NSW', 'QLD', 'NT', 'WA', 'SA', 'TAS', 'ACT') NOT NULL,

			-- The house number or other information about their address on their street, if provided.
			-- Applicants may choose not to specify.
			streetAddress VARCHAR(24),

			-- Similar to above.
			suburb VARCHAR(24),

			-- Similar to above.
			-- 
			-- Postcodes in Australia are always 4 digits and numeric, so no shenanigans here:
			-- https://en.wikipedia.org/wiki/Postcodes_in_Australia
			postcode INTEGER,

			-- Additional comments made by the applicant. Includes the 'Other skills' they specified.
			commentsAndOtherSkills TEXT,

			FOREIGN KEY (jobReferenceId) REFERENCES jobs(ref) ON DELETE CASCADE
		)");

		$this->db->query("CREATE TABLE IF NOT EXISTS eoi_skill(
			id INTEGER PRIMARY KEY AUTO_INCREMENT,

			-- ID of the EOI this skill entry belongs to.
			eoiId INTEGER NOT NULL,

			-- Name of the skill that the applicant specifies they have.
			skill VARCHAR(32) NOT NULL,

			-- Remove these skill entries when an EOI is removed.
			FOREIGN KEY (eoiId) REFERENCES eoi(id) ON DELETE CASCADE
		)");
	}

	/**
	 * Submit an expression of interest from the person with the provided details.
	 * 
	 * @return int|EoiSubmitError Unique ID of the EOI
	 */
	function submitEoi(
		string $jobReferenceId,
		string $firstName,
		string $lastName,
		string $emailAddress,
		string $phoneNumber,
		?string $gender,
		?DateTimeImmutable $dateOfBirth,
		AustraliaState $state,
		?string $streetAddress,
		?string $suburb,
		?int $postcode,
		array $skills,
		?string $commentsAndOtherSkills,
	): int|EoiSubmitError
	{
		// oh the joys of mysqli
		try
		{
			$this->db->execute_query("INSERT INTO eoi(
				jobReferenceId,
				firstName,
				lastName,
				emailAddress,
				phoneNumber,
				gender,
				dateOfBirth,
				state,
				streetAddress,
				suburb,
				postCode,
				commentsAndOtherSkills
			) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)", [
				$jobReferenceId,
				$firstName,
				$lastName,
				$emailAddress,
				$phoneNumber,
				$gender,
				$dateOfBirth->format('Y/m/d'),
				$state->value,
				$streetAddress,
				$suburb,
				$postcode,
				$commentsAndOtherSkills
			]);
		}
		catch (mysqli_sql_exception $sqlException)
		{
			// Error code for a foreign key constraint failing. In our case, this means that the job
			// listing we were given a reference for doesn't exist.
			// 
			// https://dev.mysql.com/doc/mysql-errors/8.4/en/server-error-reference.html
			if ($sqlException->getCode() === 1452)
			{
				return EoiSubmitError::NoSuchJobRef;
			}

			throw $sqlException;
		}

		$eoiId = $this->db->insert_id;

		$skillQuery = $this->db->prepare("INSERT INTO eoi_skill(eoiId, skill) VALUES (?, ?)");

		foreach ($skills as $skill)
		{
			$skillQuery->execute([$eoiId, $skill]);
		}

		$skillQuery->close();

		return $eoiId;
	}
}

readonly class Eoi
{
	function __construct(
		public int $id,
		public string $jobReferenceId,
		public string $firstName,
		public string $lastName,
		public string $emailAddress,
		public string $phoneNumber,
		public ?string $gender,
		public ?DateTimeImmutable $dateOfBirth,
		public AustraliaState $state,
		public ?string $streetAddress,
		public ?string $suburb,
		public ?int $postcode,
		public array $skills,
		public ?string $commentsAndOtherSkills,
	)
	{}
}

enum EoiSubmitError
{
	/**
	 * The job reference ID doesn't refer to any current listings.
	 */
	case NoSuchJobRef;
}

enum AustraliaState: string
{
	case Vic = 'VIC';
	case Nsw = 'NSW';
	case Qld = 'QLD';
	case Nt = 'NT';
	case Wa = 'WA';
	case Sa = 'SA';
	case Tas = 'TAS';
	case Act = 'ACT';
}
