<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostCmRepository;
use App\Model\Marketing\MktCostCm;
use App\Traits\Eloquent\MsTraits;
class MktCostCmImplementation implements MktCostCmRepository
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
	public function __construct(MktCostCm $model)
	{
		$this->model = $model;
	}
}
