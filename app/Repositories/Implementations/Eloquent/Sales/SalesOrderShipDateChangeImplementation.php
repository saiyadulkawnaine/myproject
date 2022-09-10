<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\SalesOrderShipDateChangeRepository;
use App\Model\Sales\SalesOrderShipDateChange;
use App\Traits\Eloquent\MsTraits;
class SalesOrderShipDateChangeImplementation implements SalesOrderShipDateChangeRepository
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
	public function __construct(SalesOrderShipDateChange $model)
	{
		$this->model = $model;
	}
}
