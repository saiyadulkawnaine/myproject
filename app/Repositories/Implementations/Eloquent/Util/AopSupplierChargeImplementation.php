<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\AopSupplierChargeRepository;
use App\Model\Util\AopSupplierCharge;
use App\Traits\Eloquent\MsTraits;
class AopSupplierChargeImplementation implements AopSupplierChargeRepository
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
	public function __construct(AopSupplierCharge $model)
	{
		$this->model = $model;
	}
}
