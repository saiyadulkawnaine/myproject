<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\KnitChargeSupplierRepository;
use App\Model\Util\KnitChargeSupplier;
use App\Traits\Eloquent\MsTraits;
class KnitChargeSupplierImplementation implements KnitChargeSupplierRepository
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
	public function __construct(KnitChargeSupplier $model)
	{
		$this->model = $model;
	}
}
