<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;

use App\Repositories\Contracts\Subcontract\AOP\SoAopMktCostParamItemRepository;
use App\Model\Subcontract\AOP\SoAopMktCostParamItem;
use App\Traits\Eloquent\MsTraits;

class SoAopMktCostParamItemImplementation implements SoAopMktCostParamItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingMktCostFabItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopMktCostParamItem $model)
	{
		$this->model = $model;
	}
}
