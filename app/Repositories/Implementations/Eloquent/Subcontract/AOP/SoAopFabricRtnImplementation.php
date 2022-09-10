<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRtnRepository;
use App\Model\Subcontract\AOP\SoAopFabricRtn;
use App\Traits\Eloquent\MsTraits;
class SoAopFabricRtnImplementation implements SoAopFabricRtnRepository
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
	public function __construct(SoAopFabricRtn $model)
	{
		$this->model = $model;
	}
}
