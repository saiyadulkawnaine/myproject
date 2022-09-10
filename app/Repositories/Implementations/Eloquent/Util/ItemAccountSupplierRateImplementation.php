<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemAccountSupplierRateRepository;
use App\Model\Util\ItemAccountSupplierRate;
use App\Traits\Eloquent\MsTraits;
class ItemAccountSupplierRateImplementation implements ItemAccountSupplierRateRepository
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
	public function __construct(ItemAccountSupplierRate $model)
	{
		$this->model = $model;
	}
}
