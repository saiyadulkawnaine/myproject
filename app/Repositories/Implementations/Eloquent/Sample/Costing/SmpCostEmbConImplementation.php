<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostEmbConRepository;
use App\Model\Sample\Costing\SmpCostEmbCon;
use App\Traits\Eloquent\MsTraits;
class SmpCostEmbConImplementation implements SmpCostEmbConRepository
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
	public function __construct(SmpCostEmbCon $model)
	{
		$this->model = $model;
	}
}
