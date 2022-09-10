<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\YarnDyingChargeRepository;
use App\Model\Util\YarnDyingCharge;
use App\Traits\Eloquent\MsTraits;
class YarnDyingChargeImplementation implements YarnDyingChargeRepository
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
	public function __construct(YarnDyingCharge $model)
	{
		$this->model = $model;
	}
}
