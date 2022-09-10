<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\KnitChargeRepository;
use App\Model\Util\KnitCharge;
use App\Traits\Eloquent\MsTraits;
class KnitChargeImplementation implements KnitChargeRepository
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
	public function __construct(KnitCharge $model)
	{
		$this->model = $model;
	}
}
