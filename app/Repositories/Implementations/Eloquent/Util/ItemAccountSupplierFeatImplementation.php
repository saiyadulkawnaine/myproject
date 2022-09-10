<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ItemAccountSupplierFeatRepository;
use App\Model\Util\ItemAccountSupplierFeat;
use App\Traits\Eloquent\MsTraits;
class ItemAccountSupplierFeatImplementation implements ItemAccountSupplierFeatRepository
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
	public function __construct(ItemAccountSupplierFeat $model)
	{
		$this->model = $model;
	}
}
