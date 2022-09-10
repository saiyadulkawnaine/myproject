<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoYarnItemRepository;
use App\Model\Purchase\PoYarnItem;
use App\Traits\Eloquent\MsTraits;
class PoYarnItemImplementation implements PoYarnItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoYarnItem $model)
	{
		$this->model = $model;
	}
}
