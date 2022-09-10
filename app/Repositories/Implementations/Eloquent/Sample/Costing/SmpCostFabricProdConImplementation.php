<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricProdConRepository;
use App\Model\Sample\Costing\SmpCostFabricProdCon;
use App\Traits\Eloquent\MsTraits;
class SmpCostFabricProdConImplementation implements SmpCostFabricProdConRepository
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
	public function __construct(SmpCostFabricProdCon $model)
	{
		$this->model = $model;
	}
}
