<?php

declare(strict_types=1);

namespace App\Services\Csv;

use Iterator;
use League\Csv\Reader;

final class CsvService
{
	public function getIteratorFromCsv(string $filePath): Iterator
	{
		$csvReader = Reader::createFromPath($filePath);
		$csvReader->setHeaderOffset(0);

		return $csvReader->getIterator();
	}

	public function getArrayFromCsv(string $filePath): array
	{
		return iterator_to_array($this->getIteratorFromCsv($filePath));
	}
}
