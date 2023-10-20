<?php

declare(strict_types=1);

namespace App\Services\CronDatatable;

use App\Services\Csv\CsvService;

class CronDatatableService
{
	public const ROWS_PER_DATATABLE = 20;
	private const CRON_CSV_PATH = '../csv/cron-jobs.csv';

	public function __construct(protected CsvService $csvService)
	{
	}

	private function getCronDatatableArray(): array
	{
		return $this->csvService->getArrayFromCsv(self::CRON_CSV_PATH);
	}

	public function getCronDatatableArraySliced(int $dataTableSliceOffset): array
	{
		return array_slice($this->getCronDatatableArray(), $dataTableSliceOffset, self::ROWS_PER_DATATABLE, true);
	}

	public function getCronDataTableAllRowCount(): int
	{
		return count($this->csvService->getArrayFromCsv(self::CRON_CSV_PATH));
	}

	public function getCronDatatableFilteredByTime(int $timeFrom, int $timeTo): array
	{
		$cronDataTableArray = $this->getCronDatatableArray();

		return array_filter($cronDataTableArray, function ($row) use ($timeFrom, $timeTo) {
			$averageTime = $row['Prumerny cas provedeni'];

			return $averageTime >= $timeFrom && $averageTime <= $timeTo;
		});
	}

	public function getCronDatatableFilteredBytTimeCount(int $timeFrom, int $timeTo): int
	{
		return count($this->getCronDatatableFilteredByTime($timeFrom, $timeTo));
	}

	public function getCronDatatableFilteredByTimeSliced(int $timeFrom, int $timeTo, int $dataTableSliceOffset): array
	{
		return array_slice($this->getCronDatatableFilteredByTime($timeFrom, $timeTo), $dataTableSliceOffset, self::ROWS_PER_DATATABLE, true);
	}
}
