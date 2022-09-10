<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\BuyerKnitChargeRepository;
use App\Model\Util\BuyerKnitCharge;
use App\Traits\Eloquent\MsTraits;
class BuyerKnitChargeImplementation implements BuyerKnitChargeRepository
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
	public function __construct(BuyerKnitCharge $model)
	{
		$this->model = $model;
	}
}
