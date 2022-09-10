<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricIsuItemRepository;
use App\Model\Subcontract\AOP\SoAopFabricIsuItem;
use App\Traits\Eloquent\MsTraits;
class SoAopFabricIsuItemImplementation implements SoAopFabricIsuItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopFabricIsuItem $model)
	{
		$this->model = $model;
	}
}
