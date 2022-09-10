<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopRefRepository;
use App\Model\Subcontract\AOP\SoAopRef;
use App\Traits\Eloquent\MsTraits;
class SoAopRefImplementation implements SoAopRefRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoAopRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopRef $model)
	{
		$this->model = $model;
	}
}
