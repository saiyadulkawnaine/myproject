<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostCmRepository;
use App\Model\Sample\Costing\SmpCostCm;
use App\Traits\Eloquent\MsTraits;
class SmpCostCmImplementation implements SmpCostCmRepository
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
	public function __construct(SmpCostCm $model)
	{
		$this->model = $model;
	}
}
