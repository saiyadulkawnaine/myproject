<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;

use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostQpricedtlRepository;
use App\Model\Subcontract\AOP\SoAopMktCostQpricedtl;
use App\Traits\Eloquent\MsTraits;

class SoAopMktCostQpricedtlImplementation implements SoAopMktCostQpricedtlRepository
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
	public function __construct(SoAopMktCostQpricedtl $model)
	{
		$this->model = $model;
	}
}
