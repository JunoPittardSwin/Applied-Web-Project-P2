<?php declare(strict_types=1);

/*
filename: SortDirection.php
author: Ashlyn Randall
created: 26/10/2025
last modified: 26/10/2025
description: Common sorting choices across differing database interactions.
*/

namespace DB;

/**
 * A direction to sort results in.
 */
enum SortDirection: string
{
	case Ascending = 'ASC';
	case Descending = 'DESC';
}
