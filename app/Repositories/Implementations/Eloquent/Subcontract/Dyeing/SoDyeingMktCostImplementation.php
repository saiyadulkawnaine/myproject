<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostRepository;
use App\Model\Subcontract\Dyeing\SoDyeingMktCost;
use App\Traits\Eloquent\MsTraits;
class SoDyeingMktCostImplementation implements SoDyeingMktCostRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingMktCostImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingMktCost $model)
	{
		$this->model = $model;
	}
}
