<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopPoRepository;
use App\Model\Subcontract\AOP\SoAopPo;
use App\Traits\Eloquent\MsTraits;
class SoAopPoImplementation implements SoAopPoRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoAopPoImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopPo $model)
	{
		$this->model = $model;
	}
}
