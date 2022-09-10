<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;

use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostQpriceRepository;
use App\Model\Subcontract\AOP\SoAopMktCostQprice;
use App\Traits\Eloquent\MsTraits;

class SoAopMktCostQpriceImplementation implements SoAopMktCostQpriceRepository
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
	public function __construct(SoAopMktCostQprice $model)
	{
		$this->model = $model;
	}
}