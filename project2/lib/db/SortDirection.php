<?php declare(strict_types=1);

namespace DB;

/**
 * A direction to sort results in.
 */
enum SortDirection: string
{
	case Ascending = 'ASC';
	case Descending = 'DESC';
}
