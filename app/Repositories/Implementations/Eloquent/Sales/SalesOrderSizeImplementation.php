<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\SalesOrderSizeRepository;
use App\Model\Sales\SalesOrderSize;
use App\Traits\Eloquent\MsTraits;
class SalesOrderSizeImplementation implements SalesOrderSizeRepository
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
	public function __construct(SalesOrderSize $model)
	{
		$this->model = $model;
	}
}
