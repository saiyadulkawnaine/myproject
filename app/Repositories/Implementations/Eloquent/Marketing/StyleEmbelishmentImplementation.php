<?php

namespace App\Repositories\Implementations\Eloquent\Marketing;
use App\Repositories\Contracts\Marketing\StyleEmbelishmentRepository;
use App\Model\Marketing\StyleEmbelishment;
use App\Traits\Eloquent\MsTraits;
class StyleEmbelishmentImplementation implements StyleEmbelishmentRepository
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
	public function __construct(StyleEmbelishment $model)
	{
		$this->model = $model;
	}
}
