<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\WeightMachineRepository;
use App\Model\Util\WeightMachine;
use App\Traits\Eloquent\MsTraits;
class WeightMachineImplementation implements WeightMachineRepository
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
	public function __construct(WeightMachine $model)
	{
		$this->model = $model;
	}
}
