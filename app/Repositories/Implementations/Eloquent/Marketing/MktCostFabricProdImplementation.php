<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostFabricProdRepository;
use App\Model\Marketing\MktCostFabricProd;
use App\Traits\Eloquent\MsTraits;
class MktCostFabricProdImplementation implements MktCostFabricProdRepository
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
	public function __construct(MktCostFabricProd $model)
	{
		$this->model = $model;
	}
}
