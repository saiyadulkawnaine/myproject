<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;

use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostParamRepository;
use App\Model\Subcontract\AOP\SoAopMktCostParam;
use App\Traits\Eloquent\MsTraits;

class SoAopMktCostParamImplementation implements SoAopMktCostParamRepository
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
	public function __construct(SoAopMktCostParam $model)
	{
		$this->model = $model;
	}
}
