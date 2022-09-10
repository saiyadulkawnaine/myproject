<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostCommissionRepository;
use App\Model\Marketing\MktCostCommission;
use App\Traits\Eloquent\MsTraits;
class MktCostCommissionImplementation implements MktCostCommissionRepository
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
	public function __construct(MktCostCommission $model)
	{
		$this->model = $model;
	}
}
