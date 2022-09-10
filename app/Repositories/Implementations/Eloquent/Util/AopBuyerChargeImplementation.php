<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\AopBuyerChargeRepository;
use App\Model\Util\AopBuyerCharge;
use App\Traits\Eloquent\MsTraits;
class AopBuyerChargeImplementation implements AopBuyerChargeRepository
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
	public function __construct(AopBuyerCharge $model)
	{
		$this->model = $model;
	}
}
