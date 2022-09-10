<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabRepository;
use App\Model\Subcontract\Dyeing\SoDyeingMktCostFab;
use App\Traits\Eloquent\MsTraits;
class SoDyeingMktCostFabImplementation implements SoDyeingMktCostFabRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingMktCostFabImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingMktCostFab $model)
	{
		$this->model = $model;
	}
}
