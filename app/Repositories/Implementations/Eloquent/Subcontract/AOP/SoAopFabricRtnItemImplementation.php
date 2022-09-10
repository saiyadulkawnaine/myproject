<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRtnItemRepository;
use App\Model\Subcontract\AOP\SoAopFabricRtnItem;
use App\Traits\Eloquent\MsTraits;
class SoAopFabricRtnItemImplementation implements SoAopFabricRtnItemRepository
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
	public function __construct(SoAopFabricRtnItem $model)
	{
		$this->model = $model;
	}
}
