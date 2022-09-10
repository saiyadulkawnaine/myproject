<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerYarnDyingChargeRepository;
use App\Model\Util\BuyerYarnDyingCharge;
use App\Traits\Eloquent\MsTraits;
class BuyerYarnDyingChargeImplementation implements BuyerYarnDyingChargeRepository
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
	public function __construct(BuyerYarnDyingCharge $model)
	{
		$this->model = $model;
	}
}
