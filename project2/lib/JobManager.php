<?php declare(strict_types=1);

/**
 * Manager of the job list in the database. Provides access to creating new job listings, and
 * type-safe representations of the job information in the database.
 */
class JobManager
{
	private ?mysqli_stmt $getRequirements = null;

	private ?mysqli_stmt $createJobListing = null;
	private ?mysqli_stmt $addRequirement = null;

	function __construct(private mysqli $db)
	{
		$db->execute_query("CREATE TABLE IF NOT EXISTS job(
			ref CHAR(5) NOT NULL PRIMARY KEY,
			title VARCHAR(50) NOT NULL,
			salaryLowBracket INTEGER NOT NULL,
			salaryHighBracket INTEGER NOT NULL,
			reportingLine VARCHAR(50) NOT NULL,
			aboutHtml TEXT NOT NULL,
			asideInfoHtml TEXT,
			FULLTEXT (title, aboutHtml)
		);");

		$db->execute_query("CREATE TABLE IF NOT EXISTS job_requirement(
			id INTEGER NOT NULL PRIMARY KEY AUTO_INCREMENT,
			jobId CHAR(5) NOT NULL,
			kind ENUM('Essential', 'Preferred') NOT NULL,
			text TEXT NOT NULL,
			FOREIGN KEY (jobId) REFERENCES job(ref) ON DELETE CASCADE
		);");
	}

	/**
	 * Get the list of current job listings.
	 *
	 * @param ?string $searchQuery an optional search query to filter results by.
	 * @return JobListing[]
	 */
	public function getAllJobListings(?string $searchQuery = null): array
	{
		/** @var mysqli_result */
		$result = match ($searchQuery === null)
		{
			false => $this->db->execute_query(
				"SELECT * FROM job
				 WHERE MATCH(title, aboutHtml)
				 AGAINST (? IN NATURAL LANGUAGE MODE)",
				[$searchQuery]
			),

			true => $this->db->execute_query('SELECT * FROM job'),
		};

		/** @var JobListing[] */
		$jobListings = [];

		while ($row = $result->fetch_assoc())
		{
			/** @var string[] */
			$essentialRequirements = [];

			/** @var string[] */
			$preferredRequirements = [];

			$this->getRequirements ??= $this->db->prepare(
				"SELECT kind, text FROM job_requirement
				 WHERE jobId = ?"
			);

			$this->getRequirements->execute([$row['ref']]);
			$getRequirementsResult = $this->getRequirements->get_result();
			
			while ($reqirementRow = $getRequirementsResult->fetch_assoc())
			{
				switch (JobRequirementKind::from($reqirementRow['kind']))
				{
					case JobRequirementKind::Essential:
						$essentialRequirements []= $reqirementRow['text'];
					break;

					case JobRequirementKind::Preferred:
						$preferredRequirements []= $reqirementRow['text'];
					break;
				}
			}

			$getRequirementsResult->close();
			$this->getRequirements->reset();

			$jobListings []= new JobListing(...$row,
				essentialRequirements: $essentialRequirements,
				preferredRequirements: $preferredRequirements
			);
		}

		$result->close();

		return $jobListings;
	}

	/**
	 * Get the number of current job listings.
	 *
	 * @return integer
	 */
	public function getJobListingCount(): int
	{
		$result = $this->db->query('SELECT 1 FROM job');
		$count = $result->num_rows;
		$result->close();

		return $count;
	}

	/**
	 * Create a listing for a job with the provided information.
	 */
	public function createJobListing(JobListing $job)
	{
		$this->createJobListing ??= $this->db->prepare(
			"INSERT INTO job (
				ref,
				title,
				salaryLowBracket,
				salaryHighBracket,
				reportingLine,
				aboutHtml,
				asideInfoHtml
			)
			VALUES (?, ?, ?, ?, ?, ?, ?)"
		);

		$this->createJobListing->execute([
			$job->ref,
			$job->title,
			$job->salaryLowBracket,
			$job->salaryHighBracket,
			$job->reportingLine,
			$job->aboutHtml,
			$job->asideInfoHtml,
		]);

		$this->addRequirement ??= $this->db->prepare(
			"INSERT INTO job_requirement(jobId, kind, text)
			 VALUES (?, ?, ?)"
		);

		foreach ($job->essentialRequirements as $requirementText)
		{
			$this->addRequirement->execute([
				$job->ref,
				JobRequirementKind::Essential->value,
				$requirementText
			]);
		}

		foreach ($job->preferredRequirements as $requirementText)
		{
			$this->addRequirement->execute([
				$job->ref,
				JobRequirementKind::Preferred->value,
				$requirementText
			]);
		}
	}
}

readonly class JobListing
{
	function __construct(
		public string $ref,
		public string $title,
		public int $salaryLowBracket,
		public int $salaryHighBracket,
		public string $reportingLine,
		public string $aboutHtml,
		public array $essentialRequirements,
		public array $preferredRequirements,
		public ?string $asideInfoHtml = null
	)
	{}
}

enum JobRequirementKind: string
{
	case Essential = 'Essential';
	case Preferred = 'Preferred';
}
