<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostRepository;
use App\Model\Subcontract\AOP\SoAopMktCost;
use App\Traits\Eloquent\MsTraits;
class SoAopMktCostImplementation implements SoAopMktCostRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoAopMktCostImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopMktCost $model)
	{
		$this->model = $model;
	}
}
