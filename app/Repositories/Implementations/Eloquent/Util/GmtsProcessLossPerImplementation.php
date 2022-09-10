<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\GmtsProcessLossPerRepository;
use App\Model\Util\GmtsProcessLossPer;
use App\Traits\Eloquent\MsTraits;
class GmtsProcessLossPerImplementation implements GmtsProcessLossPerRepository
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
	public function __construct(GmtsProcessLossPer $model)
	{
		$this->model = $model;
	}
}
