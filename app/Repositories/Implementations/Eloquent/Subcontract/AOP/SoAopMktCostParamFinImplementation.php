<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;

use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostParamFinRepository;
use App\Model\Subcontract\AOP\SoAopMktCostParamFin;
use App\Traits\Eloquent\MsTraits;

class SoAopMktCostParamFinImplementation implements SoAopMktCostParamFinRepository
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
	public function __construct(SoAopMktCostParamFin $model)
	{
		$this->model = $model;
	}
}
