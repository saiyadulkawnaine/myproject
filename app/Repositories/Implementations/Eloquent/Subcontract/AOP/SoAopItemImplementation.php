<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\AOP;
use App\Repositories\Contracts\Subcontract\AOP\SoAopItemRepository;
use App\Model\Subcontract\AOP\SoAopItem;
use App\Traits\Eloquent\MsTraits;
class SoAopItemImplementation implements SoAopItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoAopProductImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoAopItem $model)
	{
		$this->model = $model;
	}
}
