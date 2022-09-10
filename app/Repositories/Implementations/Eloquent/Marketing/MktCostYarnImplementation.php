<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostYarnRepository;
use App\Model\Marketing\MktCostYarn;
use App\Traits\Eloquent\MsTraits;
class MktCostYarnImplementation implements MktCostYarnRepository
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
	public function __construct(MktCostYarn $model)
	{
		$this->model = $model;
	}
}
