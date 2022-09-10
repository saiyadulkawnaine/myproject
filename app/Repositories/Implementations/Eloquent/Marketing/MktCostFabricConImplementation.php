<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostFabricConRepository;
use App\Model\Marketing\MktCostFabricCon;
use App\Traits\Eloquent\MsTraits;
class MktCostFabricConImplementation implements MktCostFabricConRepository
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
	public function __construct(MktCostFabricCon $model)
	{
		$this->model = $model;
	}
}
