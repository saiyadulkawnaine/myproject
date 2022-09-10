<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingItemRepository;
use App\Model\Subcontract\Dyeing\SoDyeingItem;
use App\Traits\Eloquent\MsTraits;
class SoDyeingItemImplementation implements SoDyeingItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingProductImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingItem $model)
	{
		$this->model = $model;
	}
}
