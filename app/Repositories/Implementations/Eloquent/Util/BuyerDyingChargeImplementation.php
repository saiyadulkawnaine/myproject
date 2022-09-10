<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerDyingChargeRepository;
use App\Model\Util\BuyerDyingCharge;
use App\Traits\Eloquent\MsTraits;
class BuyerDyingChargeImplementation implements BuyerDyingChargeRepository
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
	public function __construct(BuyerDyingCharge $model)
	{
		$this->model = $model;
	}
}
