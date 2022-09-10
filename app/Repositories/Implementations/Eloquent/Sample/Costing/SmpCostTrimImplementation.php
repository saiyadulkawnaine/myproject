<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostTrimRepository;
use App\Model\Sample\Costing\SmpCostTrim;
use App\Traits\Eloquent\MsTraits;
class SmpCostTrimImplementation implements SmpCostTrimRepository
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
	public function __construct(SmpCostTrim $model)
	{
		$this->model = $model;
	}
}
