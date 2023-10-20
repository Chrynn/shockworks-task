<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Services\CronDatatable\CronDatatableService;
use Nette\Application\UI\Form;

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
		if (!$dataTableFilterByTimeStatus) {
			$cronDataTableAllRowCount = $this->cronDatatableService->getCronDataTableAllRowCount();
			$newDataTableSliceOffset = $dataTableSliceOffset + CronDatatableService::ROWS_PER_DATATABLE;
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
		} else {
			$cronDataTableAllRowCount = $this->cronDatatableService->getCronDatatableFilteredBytTimeCount($dataTableTimeFilterTimeFrom, $dataTableTimeFilterTimeTo);
			$newDataTableSliceOffset = $dataTableSliceOffset + CronDatatableService::ROWS_PER_DATATABLE;
			$newDataTablePageIndicator = $dataTablePageIndicator + 1;
			$areNextRowsToShow = $newDataTableSliceOffset < $cronDataTableAllRowCount;

			if ($areNextRowsToShow) {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableFilteredByTimeSliced($dataTableTimeFilterTimeFrom, $dataTableTimeFilterTimeTo, $newDataTableSliceOffset);
				$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
				$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
			} else {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableFilteredByTimeSliced($dataTableTimeFilterTimeFrom, $dataTableTimeFilterTimeTo, $dataTableSliceOffset);
				$this->template->dataTableSliceOffset = $dataTableSliceOffset;
				$this->template->dataTablePageIndicator = $dataTablePageIndicator;
			}
		}

		$this->template->dataTableFilterByTimeStatus = $dataTableFilterByTimeStatus;
		$this->template->dataTableTimeFilterTimeFrom = $dataTableTimeFilterTimeFrom;
		$this->template->dataTableTimeFilterTimeTo = $dataTableTimeFilterTimeTo;
	}

	public function handleShowLastRows(
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

			if (!$dataTableFilterByTimeStatus) {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($newDataTableSliceOffset);
				$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
				$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
			} else {
				$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableFilteredByTimeSliced($dataTableTimeFilterTimeFrom, $dataTableTimeFilterTimeTo, $newDataTableSliceOffset);
				$this->template->dataTableSliceOffset = $newDataTableSliceOffset;
				$this->template->dataTablePageIndicator = $newDataTablePageIndicator;
			}
		}

		$this->template->dataTableFilterByTimeStatus = $dataTableFilterByTimeStatus;
		$this->template->dataTableTimeFilterTimeFrom = $dataTableTimeFilterTimeFrom;
		$this->template->dataTableTimeFilterTimeTo = $dataTableTimeFilterTimeTo;
	}

	protected function createComponentFilterByTimeForm(): Form
	{
		$form = new Form();
		$form->addInteger("timeFrom")
			->setRequired('Please fill timeFrom input');
		$form->addInteger("timeTo")
			->setRequired('Please fill timeTo input');
		$form->onSubmit[] = [$this, "filterByTime"];
		return $form;
	}

	public function filterByTime(Form $form): void
	{
		$formValues = $form->getValues();
		$valueTimeFrom = $formValues->timeFrom;
		$valueTimeTo = $formValues->timeTo;
		$dataTableSliceOffset = 0;
		$dataTablePageIndicator = 1;
		$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableFilteredByTimeSliced($valueTimeFrom, $valueTimeTo, $dataTableSliceOffset);
		$this->template->dataTableSliceOffset = $dataTableSliceOffset;
		$this->template->dataTablePageIndicator = $dataTablePageIndicator;
		$this->template->dataTableFilterByTimeStatus = true;
		$this->template->dataTableTimeFilterTimeFrom = $valueTimeFrom;
		$this->template->dataTableTimeFilterTimeTo = $valueTimeTo;
	}

	public function handleResetFilter(): void
	{
		$dataTableSliceOffset = 0;
		$dataTablePageIndicator = 1;
		$this->template->dataTableDataArray = $this->cronDatatableService->getCronDatatableArraySliced($dataTableSliceOffset);
		$this->template->dataTableSliceOffset = $dataTableSliceOffset;
		$this->template->dataTablePageIndicator = $dataTablePageIndicator;
		$this->template->dataTableFilterByTimeStatus = false;
		$this->template->dataTableTimeFilterTimeFrom = null;
		$this->template->dataTableTimeFilterTimeTo = null;
	}
}
