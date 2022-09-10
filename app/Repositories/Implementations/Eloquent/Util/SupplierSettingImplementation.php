<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SupplierSettingRepository;
use App\Model\Util\SupplierSetting;
use App\Traits\Eloquent\MsTraits;
class SupplierSettingImplementation implements SupplierSettingRepository
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
	public function __construct(SupplierSetting $model)
	{
		$this->model = $model;
	}
}
