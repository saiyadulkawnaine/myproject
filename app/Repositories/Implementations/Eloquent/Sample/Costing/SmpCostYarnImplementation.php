<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostYarnRepository;
use App\Model\Sample\Costing\SmpCostYarn;
use App\Traits\Eloquent\MsTraits;
class SmpCostYarnImplementation implements SmpCostYarnRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SmpCostYarn $model)
	{
		$this->model = $model;
	}
}
