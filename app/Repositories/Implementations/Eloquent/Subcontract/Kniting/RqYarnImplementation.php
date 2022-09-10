<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnRepository;
use App\Model\Subcontract\Kniting\RqYarn;
use App\Traits\Eloquent\MsTraits;
class RqYarnImplementation implements RqYarnRepository
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
	public function __construct(RqYarn $model)
	{
		$this->model = $model;
	}
}
