<?php

namespace App\Repositories\Implementations\Eloquent\Sales;

use App\Repositories\Contracts\Sales\SalesOrderCloseRepository;
use App\Model\Sales\SalesOrderClose;
use App\Traits\Eloquent\MsTraits;

class SalesOrderCloseImplementation implements SalesOrderCloseRepository
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
	public function __construct(SalesOrderClose $model)
	{
		$this->model = $model;
	}
}
