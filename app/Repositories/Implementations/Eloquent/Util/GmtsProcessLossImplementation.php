<?php

namespace App\Repositories\Implementations\Eloquent\Util;
use App\Repositories\Contracts\Util\GmtsProcessLossRepository;
use App\Model\Util\GmtsProcessLoss;
use App\Traits\Eloquent\MsTraits;
class GmtsProcessLossImplementation implements GmtsProcessLossRepository
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
	public function __construct(GmtsProcessLoss $model)
	{
		$this->model = $model;
	}
}
