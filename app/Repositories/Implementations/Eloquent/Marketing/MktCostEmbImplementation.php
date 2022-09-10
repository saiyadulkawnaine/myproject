<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostEmbRepository;
use App\Model\Marketing\MktCostEmb;
use App\Traits\Eloquent\MsTraits;
class MktCostEmbImplementation implements MktCostEmbRepository
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
	public function __construct(MktCostEmb $model)
	{
		$this->model = $model;
	}
}
