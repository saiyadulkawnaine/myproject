<?php

namespace App\Repositories\Implementations\Eloquent\FAMS;

use App\Repositories\Contracts\FAMS\AssetReturnDetailRepository;
use App\Model\FAMS\AssetReturnDetail;
use App\Traits\Eloquent\MsTraits;

class AssetReturnDetailImplementation implements AssetReturnDetailRepository
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
	public function __construct(AssetReturnDetail $model)
	{
		$this->model = $model;
	}
}
