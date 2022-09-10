<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostTrimConRepository;
use App\Model\Sample\Costing\SmpCostTrimCon;
use App\Traits\Eloquent\MsTraits;
class SmpCostTrimConImplementation implements SmpCostTrimConRepository
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
	public function __construct(SmpCostTrimCon $model)
	{
		$this->model = $model;
	}
}
