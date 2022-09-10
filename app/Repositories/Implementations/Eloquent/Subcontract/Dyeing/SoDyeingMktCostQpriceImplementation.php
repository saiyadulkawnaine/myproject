<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpriceRepository;
use App\Model\Subcontract\Dyeing\SoDyeingMktCostQprice;
use App\Traits\Eloquent\MsTraits;
class SoDyeingMktCostQpriceImplementation implements SoDyeingMktCostQpriceRepository
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
	public function __construct(SoDyeingMktCostQprice $model)
	{
		$this->model = $model;
	}
}