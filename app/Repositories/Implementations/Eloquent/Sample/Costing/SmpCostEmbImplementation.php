<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostEmbRepository;
use App\Model\Sample\Costing\SmpCostEmb;
use App\Traits\Eloquent\MsTraits;
class SmpCostEmbImplementation implements SmpCostEmbRepository
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
	public function __construct(SmpCostEmb $model)
	{
		$this->model = $model;
	}
}
