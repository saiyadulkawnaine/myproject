<?php

namespace App\Repositories\Implementations\Eloquent\FAMS;

use App\Repositories\Contracts\FAMS\AssetReturnRepository;
use App\Model\FAMS\AssetReturn;
use App\Traits\Eloquent\MsTraits;

class AssetReturnImplementation implements AssetReturnRepository
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
	public function __construct(AssetReturn $model)
	{
		$this->model = $model;
	}
}
