<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SupplierYarnDyingChargeRepository;
use App\Model\Util\SupplierYarnDyingCharge;
use App\Traits\Eloquent\MsTraits;
class SupplierYarnDyingChargeImplementation implements SupplierYarnDyingChargeRepository
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
	public function __construct(SupplierYarnDyingCharge $model)
	{
		$this->model = $model;
	}
}
