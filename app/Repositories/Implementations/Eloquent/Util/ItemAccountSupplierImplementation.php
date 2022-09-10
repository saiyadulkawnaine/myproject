<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemAccountSupplierRepository;
use App\Model\Util\ItemAccountSupplier;
use App\Traits\Eloquent\MsTraits;
class ItemAccountSupplierImplementation implements ItemAccountSupplierRepository
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
	public function __construct(ItemAccountSupplier $model)
	{
		$this->model = $model;
	}
}
