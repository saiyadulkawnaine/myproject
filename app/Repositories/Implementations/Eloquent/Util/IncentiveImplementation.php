<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\IncentiveRepository;
use App\Model\Util\Incentive;
use App\Traits\Eloquent\MsTraits;
class IncentiveImplementation implements IncentiveRepository
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
	public function __construct(Incentive $model)
	{
		$this->model = $model;
	}
}
