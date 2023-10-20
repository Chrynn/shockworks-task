<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Services\Csv\CsvService;

class MainTaskPresenter extends AbstractPresenter
{

	public function __construct(protected CsvService $csvService)
	{
		parent::__construct();
	}

	public function actionDefault(): void
	{
		$this->template->cronDataArray = $this->csvService->getArrayFromCsv(__DIR__ . '/csv/cron-jobs.csv');
	}
}
