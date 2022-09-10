<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingItemRepository;
use App\Model\Subcontract\Dyeing\PlDyeingItem;
use App\Traits\Eloquent\MsTraits;
class PlDyeingItemImplementation implements PlDyeingItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *PlKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PlDyeingItem $model)
	{
		$this->model = $model;
	}
}
