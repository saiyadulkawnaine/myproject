<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricConRepository;
use App\Model\Sample\Costing\SmpCostFabricCon;
use App\Traits\Eloquent\MsTraits;
class SmpCostFabricConImplementation implements SmpCostFabricConRepository
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
	public function __construct(SmpCostFabricCon $model)
	{
		$this->model = $model;
	}
}
