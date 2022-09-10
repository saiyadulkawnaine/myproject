<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricProdRepository;
use App\Model\Sample\Costing\SmpCostFabricProd;
use App\Traits\Eloquent\MsTraits;
class SmpCostFabricProdImplementation implements SmpCostFabricProdRepository
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
	public function __construct(SmpCostFabricProd $model)
	{
		$this->model = $model;
	}
}
