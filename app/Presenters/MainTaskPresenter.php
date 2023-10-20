<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Services\CronDatatable\CronDatatableService;

class MainTaskPresenter extends AbstractPresenter
{

	public function __construct(protected CronDatatableService $cronDatatableService)
	{
		parent::__construct();
	}

	public function actionDefault(): void
	{
		$dataTableSliceOffset = 0;
		$dataTablePageIndicator = 1;
		$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($dataTableSliceOffset);
		$this->template->dataTableSliceOffset = $dataTableSliceOffset;
		$this->template->dataTablePageIndicator = $dataTablePageIndicator;
	}

	public function handleShowNextRows(int $dataTableSliceOffset, int $dataTablePageIndicator): void
	{
		$cronDataTableAllRowCount = $this->cronDatatableService->getCronDataTableAllRowCount();
		$newDataTableSliceOffset = $dataTableSliceOffset + 20;
		$newDataTablePageIndicator = $dataTablePageIndicator + 1;
		$areNextRowsToShow = $newDataTableSliceOffset < $cronDataTableAllRowCount;

		if ($areNextRowsToShow) {
			$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($newDataTableSliceOffset);
			$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
			$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
		} else {
			$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($dataTableSliceOffset);
			$this->template->dataTableSliceOffset = $dataTableSliceOffset;
			$this->template->dataTablePageIndicator = $dataTablePageIndicator;
		}
	}

	public function handleShowLastRows(int $dataTableSliceOffset, int $dataTablePageIndicator): void
	{
		if ($dataTableSliceOffset !== 0) {
			$newDataTableSliceOffset = $dataTableSliceOffset - 20;
			$newDataTablePageIndicator = $dataTablePageIndicator - 1;
			$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($newDataTableSliceOffset);
			$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
			$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
		}
	}
}
