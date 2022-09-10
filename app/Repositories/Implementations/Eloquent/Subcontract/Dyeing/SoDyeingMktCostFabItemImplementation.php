<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabItemRepository;
use App\Model\Subcontract\Dyeing\SoDyeingMktCostFabItem;
use App\Traits\Eloquent\MsTraits;
class SoDyeingMktCostFabItemImplementation implements SoDyeingMktCostFabItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingMktCostFabItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingMktCostFabItem $model)
	{
		$this->model = $model;
	}
}
