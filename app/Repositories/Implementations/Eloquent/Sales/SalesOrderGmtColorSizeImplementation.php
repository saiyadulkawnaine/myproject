<?php

namespace App\Repositories\Implementations\Eloquent\Sales;
use App\Repositories\Contracts\Sales\SalesOrderGmtColorSizeRepository;
use App\Model\Sales\SalesOrderGmtColorSize;
use App\Traits\Eloquent\MsTraits;
class SalesOrderGmtColorSizeImplementation implements SalesOrderGmtColorSizeRepository
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
	public function __construct(SalesOrderGmtColorSize $model)
	{
		$this->model = $model;
	}
}
