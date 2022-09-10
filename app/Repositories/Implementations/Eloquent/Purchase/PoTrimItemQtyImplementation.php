<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PoTrimItemQtyRepository;
use App\Model\Purchase\PoTrimItemQty;
use App\Traits\Eloquent\MsTraits;
class PoTrimItemQtyImplementation implements PoTrimItemQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * PoTrimItemQtyImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PoTrimItemQty $model)
	{
		$this->model = $model;
	}
}
