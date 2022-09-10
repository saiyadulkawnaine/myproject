<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoGeneralServiceRepository;
use App\Model\Purchase\PoGeneralService;
use App\Traits\Eloquent\MsTraits;
class PoGeneralServiceImplementation implements PoGeneralServiceRepository
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
	public function __construct(PoGeneralService $model)
	{
		$this->model = $model;
	}
}
