<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\PlDyeingRepository;
use App\Model\Subcontract\Dyeing\PlDyeing;
use App\Traits\Eloquent\MsTraits;
class PlDyeingImplementation implements PlDyeingRepository
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
	public function __construct(PlDyeing $model)
	{
		$this->model = $model;
	}
}
