<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\CapacityDistRepository;
use App\Model\Util\CapacityDist;
use App\Traits\Eloquent\MsTraits;
class CapacityDistImplementation implements CapacityDistRepository
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
	public function __construct(CapacityDist $model)
	{
		$this->model = $model;
	}
}
