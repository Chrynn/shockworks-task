<?php

declare(strict_types=1);

namespace App\Services\Csv;

use Iterator;
use League\Csv\Reader;

final class CsvService
{
	public function getArrayFromCsv(string $filePath): Iterator
	{
		$reader = Reader::createFromPath($filePath);
		$reader->setHeaderOffset(0);

		return $reader->getIterator();
	}
}