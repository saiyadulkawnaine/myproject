<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoYarnItemBomQtyRepository;
use App\Model\Purchase\PoYarnItemBomQty;
use App\Traits\Eloquent\MsTraits;
class PoYarnItemBomQtyImplementation implements PoYarnItemBomQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnItemBomQtyImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoYarnItemBomQty $model)
	{
		$this->model = $model;
	}
}
