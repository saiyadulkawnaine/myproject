<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopFabricRcvItemRepository;
use App\Model\Subcontract\AOP\SoAopFabricRcvItem;
use App\Traits\Eloquent\MsTraits;
class SoAopFabricRcvItemImplementation implements SoAopFabricRcvItemRepository
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
	public function __construct(SoAopFabricRcvItem $model)
	{
		$this->model = $model;
	}
}
