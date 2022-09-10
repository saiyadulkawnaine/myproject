<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemQtyRepository;
use App\Model\Subcontract\Kniting\PlKnitItemQty;
use App\Traits\Eloquent\MsTraits;
class PlKnitItemQtyImplementation implements PlKnitItemQtyRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *PlKnitItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PlKnitItemQty $model)
	{
		$this->model = $model;
	}
}
