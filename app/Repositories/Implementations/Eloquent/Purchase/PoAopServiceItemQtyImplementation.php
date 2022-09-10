<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoAopServiceItemQtyRepository;
use App\Model\Purchase\PoAopServiceItemQty;
use App\Traits\Eloquent\MsTraits;
class PoAopServiceItemQtyImplementation implements PoAopServiceItemQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoAopServiceItemQtyImplementation constructor.
	 *
	 * @param App\Model\Purchase\PoAopServiceItemQty $model
	 */
	public function __construct(PoAopServiceItemQty $model)
	{
		$this->model = $model;
	}
}
