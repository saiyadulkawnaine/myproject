<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\DelaycauseRepository;
use App\Model\Util\Delaycause;
use App\Traits\Eloquent\MsTraits;
class DelaycauseImplementation implements DelaycauseRepository
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
	public function __construct(Delaycause $model)
	{
		$this->model = $model;
	}
}
