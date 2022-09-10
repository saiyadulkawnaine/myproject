<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\DyingChargeRepository;
use App\Model\Util\DyingCharge;
use App\Traits\Eloquent\MsTraits;
class DyingChargeImplementation implements DyingChargeRepository
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
	public function __construct(DyingCharge $model)
	{
		$this->model = $model;
	}
}
