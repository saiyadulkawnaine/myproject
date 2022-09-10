<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitRepository;
use App\Model\Production\Kniting\ProdKnit;
use App\Traits\Eloquent\MsTraits;

class ProdKnitImplementation implements ProdKnitRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * ProdKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProdKnit $model)
	{
		$this->model = $model;
	}
}
