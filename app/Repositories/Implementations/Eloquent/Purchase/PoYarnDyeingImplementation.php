<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoYarnDyeingRepository;
use App\Model\Purchase\PoYarnDyeing;
use App\Traits\Eloquent\MsTraits;
class PoYarnDyeingImplementation implements PoYarnDyeingRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnDyeingImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoYarnDyeing $model)
	{
		$this->model = $model;
	}
}
