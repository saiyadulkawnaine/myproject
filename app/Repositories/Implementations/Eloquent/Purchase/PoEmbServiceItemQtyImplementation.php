<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoEmbServiceItemQtyRepository;
use App\Model\Purchase\PoEmbServiceItemQty;
use App\Traits\Eloquent\MsTraits;
class PoEmbServiceItemQtyImplementation implements PoEmbServiceItemQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoEmbServiceItemQtyImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoEmbServiceItemQty $model)
	{
		$this->model = $model;
	}
}
