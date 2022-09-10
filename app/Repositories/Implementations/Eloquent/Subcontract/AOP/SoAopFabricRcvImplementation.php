<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvRepository;
use App\Model\Subcontract\AOP\SoAopFabricRcv;
use App\Traits\Eloquent\MsTraits;
class SoAopFabricRcvImplementation implements SoAopFabricRcvRepository
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
	public function __construct(SoAopFabricRcv $model)
	{
		$this->model = $model;
	}
}
