<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitItemRepository;
use App\Model\Subcontract\Kniting\PlKnitItem;
use App\Traits\Eloquent\MsTraits;
class PlKnitItemImplementation implements PlKnitItemRepository
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
	public function __construct(PlKnitItem $model)
	{
		$this->model = $model;
	}
}
