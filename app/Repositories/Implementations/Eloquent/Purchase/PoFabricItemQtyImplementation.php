<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoFabricItemQtyRepository;
use App\Model\Purchase\PoFabricItemQty;
use App\Traits\Eloquent\MsTraits;
class PoFabricItemQtyImplementation implements PoFabricItemQtyRepository
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
	public function __construct(PoFabricItemQty $model)
	{
		$this->model = $model;
	}
}
