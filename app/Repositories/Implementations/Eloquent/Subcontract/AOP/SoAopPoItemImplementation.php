<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopPoItemRepository;
use App\Model\Subcontract\AOP\SoAopPoItem;
use App\Traits\Eloquent\MsTraits;
class SoAopPoItemImplementation implements SoAopPoItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoAopPoItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopPoItem $model)
	{
		$this->model = $model;
	}
}
