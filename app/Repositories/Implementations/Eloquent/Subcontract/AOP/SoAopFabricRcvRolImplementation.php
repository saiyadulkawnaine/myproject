<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRolRepository;
use App\Model\Subcontract\AOP\SoAopFabricRcvRol;
use App\Traits\Eloquent\MsTraits;
class SoAopFabricRcvRolImplementation implements SoAopFabricRcvRolRepository
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
	public function __construct(SoAopFabricRcvRol $model)
	{
		$this->model = $model;
	}
}
