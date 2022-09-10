<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\DyingChargeSupplierRepository;
use App\Model\Util\DyingChargeSupplier;
use App\Traits\Eloquent\MsTraits;
class DyingChargeSupplierImplementation implements DyingChargeSupplierRepository
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
	public function __construct(DyingChargeSupplier $model)
	{
		$this->model = $model;
	}
}
