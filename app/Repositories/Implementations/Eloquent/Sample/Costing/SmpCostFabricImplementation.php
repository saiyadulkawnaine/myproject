<?php

namespace App\Repositories\Implementations\Eloquent\Sample\Costing;
use App\Repositories\Contracts\Sample\Costing\SmpCostFabricRepository;
use App\Model\Sample\Costing\SmpCostFabric;
use App\Traits\Eloquent\MsTraits;
class SmpCostFabricImplementation implements SmpCostFabricRepository
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
	public function __construct(SmpCostFabric $model)
	{
		$this->model = $model;
	}
	
	
}
