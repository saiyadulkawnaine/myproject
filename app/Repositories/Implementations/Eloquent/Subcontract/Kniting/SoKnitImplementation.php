<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitRepository;
use App\Model\Subcontract\Kniting\SoKnit;
use App\Traits\Eloquent\MsTraits;
class SoKnitImplementation implements SoKnitRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnit $model)
	{
		$this->model = $model;
	}
}
