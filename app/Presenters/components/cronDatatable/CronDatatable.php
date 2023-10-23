<?php

declare(strict_types=1);

namespace App\Presenters\components\cronDatatable;

use App\Presenters\BaseControl;
use App\Services\CronDatatable\CronDatatableService;
use Nette\Application\UI\Form;

class CronDatatable extends BaseControl
{
	public function __construct(protected CronDatatableService $cronDatatableService)
	{
	}

	protected function createComponentForm(): Form
	{
		$form = new Form();
		$form->addInteger("timeFrom")
			->setRequired('Please fill timeFrom input');
		$form->addInteger("timeTo")
			->setRequired('Please fill timeTo input');
		$form->onSubmit[] = [$this, "filterByTime"];
		return $form;
	}

	public function showNextRows(
		int $dataTableSliceOffset,
		int $dataTablePageIndicator,
		bool $dataTableFilterByTimeStatus,
		int|null $dataTableTimeFilterTimeFrom,
		int|null $dataTableTimeFilterTimeTo,
	): void
	{
		$cronDataTableArray = $this->cronDatatableService->getCronDatatableArray();

		if (!$dataTableFilterByTimeStatus) {
			$cronDataTableAllRowCount = $this->cronDatatableService->getCronDatatableArrayRowCount($cronDataTableArray);
			$newDataTableSliceOffset = $dataTableSliceOffset + CronDatatableService::ROWS_PER_DATATABLE;
			$newDataTablePageIndicator = $dataTablePageIndicator + 1;
			$areNextRowsToShow = $newDataTableSliceOffset < $cronDataTableAllRowCount;
			$cronDatatablePageIndicatorTotal = $this->cronDatatableService->getCronDatatablePageCount($cronDataTableAllRowCount);

			if ($areNextRowsToShow) {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDataTableArray, $newDataTableSliceOffset);
				$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
				$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
			} else {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDataTableArray, $dataTableSliceOffset);
				$this->template->dataTableSliceOffset = $dataTableSliceOffset;
				$this->template->dataTablePageIndicator = $dataTablePageIndicator;
			}
		} else {
			$cronDatatableArrayFilteredByTime = $this->cronDatatableService->getCronDatatableArrayFilteredByTime($cronDataTableArray, $dataTableTimeFilterTimeFrom, $dataTableTimeFilterTimeTo);
			$cronDataTableAllRowCount = $this->cronDatatableService->getCronDatatableArrayRowCount($cronDatatableArrayFilteredByTime);
			$newDataTableSliceOffset = $dataTableSliceOffset + CronDatatableService::ROWS_PER_DATATABLE;
			$newDataTablePageIndicator = $dataTablePageIndicator + 1;
			$areNextRowsToShow = $newDataTableSliceOffset < $cronDataTableAllRowCount;
			$cronDatatablePageIndicatorTotal = $this->cronDatatableService->getCronDatatablePageCount($cronDataTableAllRowCount);

			if ($areNextRowsToShow) {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDatatableArrayFilteredByTime, $newDataTableSliceOffset);
				$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
				$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
			} else {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDatatableArrayFilteredByTime, $dataTableSliceOffset);
				$this->template->dataTableSliceOffset = $dataTableSliceOffset;
				$this->template->dataTablePageIndicator = $dataTablePageIndicator;
			}
		}

		$this->template->dataTablePageIndicatorTotal = $cronDatatablePageIndicatorTotal;
		$this->template->dataTableFilterByTimeStatus = $dataTableFilterByTimeStatus;
		$this->template->dataTableTimeFilterTimeFrom = $dataTableTimeFilterTimeFrom;
		$this->template->dataTableTimeFilterTimeTo = $dataTableTimeFilterTimeTo;
	}

	public function showLastRows(
		int $dataTableSliceOffset,
		int $dataTablePageIndicator,
		bool $dataTableFilterByTimeStatus,
		int|null $dataTableTimeFilterTimeFrom,
		int|null $dataTableTimeFilterTimeTo,
	): void
	{
		if ($dataTableSliceOffset !== 0) {
			$newDataTableSliceOffset = $dataTableSliceOffset - CronDatatableService::ROWS_PER_DATATABLE;
			$newDataTablePageIndicator = $dataTablePageIndicator - 1;
			$cronDataTableArray = $this->cronDatatableService->getCronDatatableArray();

			if (!$dataTableFilterByTimeStatus) {
				$cronDataTableAllRowCount = $this->cronDatatableService->getCronDatatableArrayRowCount($cronDataTableArray);

				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDataTableArray, $newDataTableSliceOffset);
				$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
				$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
			} else {
				$cronDatatableArrayFilteredByTime = $this->cronDatatableService->getCronDatatableArrayFilteredByTime($cronDataTableArray, $dataTableTimeFilterTimeFrom, $dataTableTimeFilterTimeTo);
				$cronDataTableAllRowCount = $this->cronDatatableService->getCronDatatableArrayRowCount($cronDatatableArrayFilteredByTime);

				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDatatableArrayFilteredByTime, $newDataTableSliceOffset);
				$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
				$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
			}

			$cronDatatablePageIndicatorTotal = $this->cronDatatableService->getCronDatatablePageCount($cronDataTableAllRowCount);
			$this->template->dataTablePageIndicatorTotal = $cronDatatablePageIndicatorTotal;
		}

		$this->template->dataTableFilterByTimeStatus = $dataTableFilterByTimeStatus;
		$this->template->dataTableTimeFilterTimeFrom = $dataTableTimeFilterTimeFrom;
		$this->template->dataTableTimeFilterTimeTo = $dataTableTimeFilterTimeTo;
	}

	public function filterByTime(Form $form): void
	{
		$formValues = $form->getValues();
		$valueTimeFrom = $formValues->timeFrom;
		$valueTimeTo = $formValues->timeTo;

		$dataTableSliceOffset = 0;
		$dataTablePageIndicator = 1;
		$cronDataTableArray = $this->cronDatatableService->getCronDatatableArray();
		$cronDatatableArrayFilteredByTime = $this->cronDatatableService->getCronDatatableArrayFilteredByTime($cronDataTableArray, $valueTimeFrom, $valueTimeTo);
		$cronDatatableArrayRowCount = $this->cronDatatableService->getCronDatatableArrayRowCount($cronDatatableArrayFilteredByTime);

		$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDatatableArrayFilteredByTime, $dataTableSliceOffset);
		$this->template->dataTableSliceOffset = $dataTableSliceOffset;
		$this->template->dataTablePageIndicator = $dataTablePageIndicator;
		$this->template->dataTablePageIndicatorTotal = $this->cronDatatableService->getCronDatatablePageCount($cronDatatableArrayRowCount);
		$this->template->dataTableFilterByTimeStatus = true;
		$this->template->dataTableTimeFilterTimeFrom = $valueTimeFrom;
		$this->template->dataTableTimeFilterTimeTo = $valueTimeTo;
	}

	public function resetFilter(): void
	{
		$dataTableSliceOffset = 0;
		$dataTablePageIndicator = 1;
		$cronDataTableArray = $this->cronDatatableService->getCronDatatableArray();
		$cronDatatableArrayRowCount = $this->cronDatatableService->getCronDatatableArrayRowCount($cronDataTableArray);

		$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($cronDataTableArray, $dataTableSliceOffset);
		$this->template->dataTableSliceOffset = $dataTableSliceOffset;
		$this->template->dataTablePageIndicator = $dataTablePageIndicator;
		$this->template->dataTablePageIndicatorTotal = $this->cronDatatableService->getCronDatatablePageCount($cronDatatableArrayRowCount);
		$this->template->dataTableFilterByTimeStatus = false;
		$this->template->dataTableTimeFilterTimeFrom = null;
		$this->template->dataTableTimeFilterTimeTo = null;
	}

	public function handleShowNextRows(
		int $dataTableSliceOffset,
		int $dataTablePageIndicator,
		bool $dataTableFilterByTimeStatus,
		int|null $dataTableTimeFilterTimeFrom,
		int|null $dataTableTimeFilterTimeTo,
	): void
	{
		$this->showNextRows(
			$dataTableSliceOffset,
			$dataTablePageIndicator,
			$dataTableFilterByTimeStatus,
			$dataTableTimeFilterTimeFrom,
			$dataTableTimeFilterTimeTo
		);
	}

	public function handleShowLastRows(
		int $dataTableSliceOffset,
		int $dataTablePageIndicator,
		bool $dataTableFilterByTimeStatus,
		int|null $dataTableTimeFilterTimeFrom,
		int|null $dataTableTimeFilterTimeTo,
	): void
	{
		$this->showLastRows(
			$dataTableSliceOffset,
			$dataTablePageIndicator,
			$dataTableFilterByTimeStatus,
			$dataTableTimeFilterTimeFrom,
			$dataTableTimeFilterTimeTo
		);
	}

	public function handleResetFilter(): void
	{
		$this->resetFilter();
	}

	public function render(): void
	{
		$this->getTemplate()->setFile(__DIR__ . "/templates/cronDatatable.latte");
		$this->getTemplate()->render();
	}
}
