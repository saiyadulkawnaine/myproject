<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoYarnDyeingItemRepository;
use App\Model\Purchase\PoYarnDyeingItem;
use App\Traits\Eloquent\MsTraits;
class PoYarnDyeingItemImplementation implements PoYarnDyeingItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoYarnDyeingItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoYarnDyeingItem $model)
	{
		$this->model = $model;
	}
}
