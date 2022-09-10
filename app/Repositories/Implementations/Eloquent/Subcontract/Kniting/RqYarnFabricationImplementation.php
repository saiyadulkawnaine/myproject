<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\RqYarnFabricationRepository;
use App\Model\Subcontract\Kniting\RqYarnFabrication;
use App\Traits\Eloquent\MsTraits;
class RqYarnFabricationImplementation implements RqYarnFabricationRepository
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
	public function __construct(RqYarnFabrication $model)
	{
		$this->model = $model;
	}
}
