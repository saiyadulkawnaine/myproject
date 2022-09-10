<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoFabricItemRepository;
use App\Model\Purchase\PoFabricItem;
use App\Traits\Eloquent\MsTraits;
class PoFabricItemImplementation implements PoFabricItemRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoFabricItem $model)
	{
		$this->model = $model;
	}
}
