<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemQtyRepository;
use App\Model\Subcontract\Dyeing\PlDyeingItemQty;
use App\Traits\Eloquent\MsTraits;
class PlDyeingItemQtyImplementation implements PlDyeingItemQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *PlKnitItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PlDyeingItemQty $model)
	{
		$this->model = $model;
	}
}
