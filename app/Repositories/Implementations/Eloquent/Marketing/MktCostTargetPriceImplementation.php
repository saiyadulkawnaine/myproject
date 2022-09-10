<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostTargetPriceRepository;
use App\Model\Marketing\MktCostTargetPrice;
use App\Traits\Eloquent\MsTraits;
class MktCostTargetPriceImplementation implements MktCostTargetPriceRepository
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
	public function __construct(MktCostTargetPrice $model)
	{
		$this->model = $model;
	}
}
