<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitDlvItemYarnRepository;
use App\Model\Subcontract\Kniting\SoKnitDlvItemYarn;
use App\Traits\Eloquent\MsTraits;
class SoKnitDlvItemYarnImplementation implements SoKnitDlvItemYarnRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitDlvItemYarn $model)
	{
		$this->model = $model;
	}
}
