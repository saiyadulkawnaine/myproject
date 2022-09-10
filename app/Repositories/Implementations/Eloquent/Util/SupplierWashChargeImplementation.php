<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SupplierWashChargeRepository;
use App\Model\Util\SupplierWashCharge;
use App\Traits\Eloquent\MsTraits;
class SupplierWashChargeImplementation implements SupplierWashChargeRepository
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
	public function __construct(SupplierWashCharge $model)
	{
		$this->model = $model;
	}
}
