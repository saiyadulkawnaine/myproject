<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostProfitRepository;
use App\Model\Marketing\MktCostProfit;
use App\Traits\Eloquent\MsTraits;
class MktCostProfitImplementation implements MktCostProfitRepository
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
	public function __construct(MktCostProfit $model)
	{
		$this->model = $model;
	}
}
