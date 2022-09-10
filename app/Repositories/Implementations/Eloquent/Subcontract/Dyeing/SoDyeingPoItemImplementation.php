<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingPoItemRepository;
use App\Model\Subcontract\Dyeing\SoDyeingPoItem;
use App\Traits\Eloquent\MsTraits;
class SoDyeingPoItemImplementation implements SoDyeingPoItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingPoItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingPoItem $model)
	{
		$this->model = $model;
	}
}
