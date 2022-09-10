<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\MktCostFabricRepository;
use App\Model\Marketing\MktCostFabric;
use App\Traits\Eloquent\MsTraits;
class MktCostFabricImplementation implements MktCostFabricRepository
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
	public function __construct(MktCostFabric $model)
	{
		$this->model = $model;
	}
	
	
}
