<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricIsuRepository;
use App\Model\Subcontract\AOP\SoAopFabricIsu;
use App\Traits\Eloquent\MsTraits;
class SoAopFabricIsuImplementation implements SoAopFabricIsuRepository
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
	public function __construct(SoAopFabricIsu $model)
	{
		$this->model = $model;
	}
}
