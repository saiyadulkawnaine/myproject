<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemBomQtyRepository;
use App\Model\Purchase\PoYarnDyeingItemBomQty;
use App\Traits\Eloquent\MsTraits;
class PoYarnDyeingItemBomQtyImplementation implements PoYarnDyeingItemBomQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnDyeingItemBomQtyImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoYarnDyeingItemBomQty $model)
	{
		$this->model = $model;
	}
}
