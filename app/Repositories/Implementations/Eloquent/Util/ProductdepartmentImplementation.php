<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\ProductdepartmentRepository;
use App\Model\Util\Productdepartment;
use App\Traits\Eloquent\MsTraits;
class ProductdepartmentImplementation implements ProductdepartmentRepository
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
	public function __construct(Productdepartment $model)
	{
		$this->model = $model;
	}
}
