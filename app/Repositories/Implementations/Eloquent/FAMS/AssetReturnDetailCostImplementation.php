<?php

namespace App\Repositories\Implementations\Eloquent\FAMS;

use App\Repositories\Contracts\FAMS\AssetReturnDetailCostRepository;
use App\Model\FAMS\AssetReturnDetailCost;
use App\Traits\Eloquent\MsTraits;

class AssetReturnDetailCostImplementation implements AssetReturnDetailCostRepository
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
	public function __construct(AssetReturnDetailCost $model)
	{
		$this->model = $model;
	}
}
