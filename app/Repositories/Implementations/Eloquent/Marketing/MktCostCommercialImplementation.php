<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostCommercialRepository;
use App\Model\Marketing\MktCostCommercial;
use App\Traits\Eloquent\MsTraits;
class MktCostCommercialImplementation implements MktCostCommercialRepository
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
	public function __construct(MktCostCommercial $model)
	{
		$this->model = $model;
	}
}
