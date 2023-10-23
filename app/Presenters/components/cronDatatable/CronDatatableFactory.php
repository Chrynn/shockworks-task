<?php

declare(strict_types=1);

namespace App\Presenters\components\cronDatatable;

interface CronDatatableFactory
{
	public function create(): CronDatatable;
}
