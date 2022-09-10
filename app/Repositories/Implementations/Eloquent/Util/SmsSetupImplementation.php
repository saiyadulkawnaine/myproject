<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\SmsSetupRepository;
use App\Model\Util\SmsSetup;
use App\Traits\Eloquent\MsTraits;
class SmsSetupImplementation implements SmsSetupRepository
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
	public function __construct(SmsSetup $model)
	{
		$this->model = $model;
	}
}