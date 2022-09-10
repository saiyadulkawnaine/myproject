<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\TargetProcessSetupRepository;
use App\Model\Util\TargetProcessSetup;
use App\Traits\Eloquent\MsTraits;
class TargetProcessSetupImplementation implements TargetProcessSetupRepository
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
	public function __construct(TargetProcessSetup $model)
	{
		$this->model = $model;
	}
}
