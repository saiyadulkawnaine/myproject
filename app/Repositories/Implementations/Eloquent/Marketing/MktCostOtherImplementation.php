<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostOtherRepository;
use App\Model\Marketing\MktCostOther;
use App\Traits\Eloquent\MsTraits;
class MktCostOtherImplementation implements MktCostOtherRepository
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
	public function __construct(MktCostOther $model)
	{
		$this->model = $model;
	}
}
