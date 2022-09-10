<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\AopChargeRepository;
use App\Model\Util\AopCharge;
use App\Traits\Eloquent\MsTraits;
class AopChargeImplementation implements AopChargeRepository
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
	public function __construct(AopCharge $model)
	{
		$this->model = $model;
	}
}
