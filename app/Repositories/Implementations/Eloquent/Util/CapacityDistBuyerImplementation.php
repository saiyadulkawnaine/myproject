<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CapacityDistBuyerRepository;
use App\Model\Util\CapacityDistBuyer;
use App\Traits\Eloquent\MsTraits;
class CapacityDistBuyerImplementation implements CapacityDistBuyerRepository
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
	public function __construct(CapacityDistBuyer $model)
	{
		$this->model = $model;
	}
}
