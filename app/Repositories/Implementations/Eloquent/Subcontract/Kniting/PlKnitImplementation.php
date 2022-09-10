<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\PlKnitRepository;
use App\Model\Subcontract\Kniting\PlKnit;
use App\Traits\Eloquent\MsTraits;
class PlKnitImplementation implements PlKnitRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *PlKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(PlKnit $model)
	{
		$this->model = $model;
	}
}
