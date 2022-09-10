<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingMktCostQpricedtlRepository;
use App\Model\Subcontract\Dyeing\SoDyeingMktCostQpricedtl;
use App\Traits\Eloquent\MsTraits;
class SoDyeingMktCostQpricedtlImplementation implements SoDyeingMktCostQpricedtlRepository
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
	public function __construct(SoDyeingMktCostQpricedtl $model)
	{
		$this->model = $model;
	}
}
