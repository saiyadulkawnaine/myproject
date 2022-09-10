<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoYarnRepository;
use App\Model\Purchase\PoYarn;
use App\Traits\Eloquent\MsTraits;
class PoYarnImplementation implements PoYarnRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoYarn $model)
	{
		$this->model = $model;
	}
}
