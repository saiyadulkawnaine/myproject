<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoKnitServiceItemQtyRepository;
use App\Model\Purchase\PoKnitServiceItemQty;
use App\Traits\Eloquent\MsTraits;
class PoKnitServiceItemQtyImplementation implements PoKnitServiceItemQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoKnitServiceItemQtyImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoKnitServiceItemQty $model)
	{
		$this->model = $model;
	}
}
