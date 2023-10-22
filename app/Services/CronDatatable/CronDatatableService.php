<?php

declare(strict_types=1);

namespace App\Services\CronDatatable;

use App\Services\Csv\CsvService;

class CronDatatableService implements CronDatatableServiceInterface
{
	public const ROWS_PER_DATATABLE = 20;
	private const CRON_CSV_PATH = '../csv/cron-jobs.csv';

	public function __construct(protected CsvService $csvService)
	{
	}

	public function getCronDatatableArray(): array
	{
		return $this->csvService->getArrayFromCsv(self::CRON_CSV_PATH);
	}

	public function getCronDatatableArraySliced(array $cronDatatableArray, int $dataTableSliceOffset): array
	{
		return array_slice($cronDatatableArray, $dataTableSliceOffset, self::ROWS_PER_DATATABLE, true);
	}

	public function getCronDatatableArrayRowCount(array $cronDatatableArray): int
	{
		return count($cronDatatableArray);
	}

	public function getCronDatatablePageCount(int $cronDataTableRowCount): int
	{
		return (int) ceil($cronDataTableRowCount / self::ROWS_PER_DATATABLE);
	}

	public function getCronDatatableArrayFilteredByTime(array $cronDatatableArray, int $timeFrom, int $timeTo): array
	{
		return array_filter($cronDatatableArray, function ($row) use ($timeFrom, $timeTo) {
			$averageTime = $row['Prumerny cas provedeni'];

			return $averageTime >= $timeFrom && $averageTime <= $timeTo;
		});
	}
}
