<?php

declare(strict_types=1);

namespace App\Services\CronDatatable;

interface CronDatatableServiceInterface
{
	public function getCronDatatableArray(): array;

	public function getCronDatatableArraySliced(array $cronDatatableArray, int $dataTableSliceOffset): array;

	public function getCronDatatableArrayRowCount(array $cronDatatableArray): int;

	public function getCronDatatablePageCount(int $cronDataTableRowCount): int;

	public function getCronDatatableArrayFilteredByTime(array $cronDatatableArray, int $timeFrom, int $timeTo): array;
}
