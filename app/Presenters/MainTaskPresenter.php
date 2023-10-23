<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Presenters\components\cronDatatable\CronDatatable;
use App\Presenters\components\cronDatatable\CronDatatableFactory;

class MainTaskPresenter extends BasePresenter
{

	public function __construct(protected CronDatatableFactory $cronDatatableFactory)
	{
		parent::__construct();
	}

	public function actionDefault(): void
	{
	}

	protected function createComponentCronDatatable(): CronDatatable
	{
		$cronDatatableComponent = $this->cronDatatableFactory->create();
		$cronDatatableComponent->resetFilter();

		return $cronDatatableComponent;
	}
}
