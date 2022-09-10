<?php

namespace App\Repositories\Implementations\Eloquent\FAMS;

use App\Repositories\Contracts\FAMS\AssetServiceRepository;
use App\Model\FAMS\AssetService;
use App\Traits\Eloquent\MsTraits;

class AssetServiceImplementation implements AssetServiceRepository
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
	public function __construct(AssetService $model)
	{
		$this->model = $model;
	}
}
