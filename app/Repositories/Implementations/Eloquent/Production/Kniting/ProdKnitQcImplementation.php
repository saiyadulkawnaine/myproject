<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitQcRepository;
use App\Model\Production\Kniting\ProdKnitQc;
use App\Traits\Eloquent\MsTraits;

class ProdKnitQcImplementation implements ProdKnitQcRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * ProdKnitItemImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProdKnitQc $model)
	{
		$this->model = $model;
	}
}
