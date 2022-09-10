<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostTrimRepository;
use App\Model\Marketing\MktCostTrim;
use App\Traits\Eloquent\MsTraits;
class MktCostTrimImplementation implements MktCostTrimRepository
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
	public function __construct(MktCostTrim $model)
	{
		$this->model = $model;
	}
}
