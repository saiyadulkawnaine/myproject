<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitItemRollRepository;
use App\Model\Production\Kniting\ProdKnitItemRoll;
use App\Traits\Eloquent\MsTraits;

class ProdKnitItemRollImplementation implements ProdKnitItemRollRepository
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
	public function __construct(ProdKnitItemRoll $model)
	{
		$this->model = $model;
	}
}
