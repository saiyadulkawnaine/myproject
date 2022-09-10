<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoGeneralRepository;
use App\Model\Purchase\PoGeneral;
use App\Traits\Eloquent\MsTraits;
class PoGeneralImplementation implements PoGeneralRepository
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
	public function __construct(PoGeneral $model)
	{
		$this->model = $model;
	}
}
