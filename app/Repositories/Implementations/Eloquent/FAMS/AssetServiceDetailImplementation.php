<?php

namespace App\Repositories\Implementations\Eloquent\FAMS;

use App\Repositories\Contracts\FAMS\AssetServiceDetailRepository;
use App\Model\FAMS\AssetServiceDetail;
use App\Traits\Eloquent\MsTraits;

class AssetServiceDetailImplementation implements AssetServiceDetailRepository
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
	public function __construct(AssetServiceDetail $model)
	{
		$this->model = $model;
	}
}
