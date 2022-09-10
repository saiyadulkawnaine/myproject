<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostFabFinRepository;
use App\Model\Subcontract\Dyeing\SoDyeingMktCostFabFin;
use App\Traits\Eloquent\MsTraits;
class SoDyeingMktCostFabFinImplementation implements SoDyeingMktCostFabFinRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingMktCostFabFinImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingMktCostFabFin $model)
	{
		$this->model = $model;
	}
}
