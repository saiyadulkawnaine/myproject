<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitItemYarnRepository;
use App\Model\Production\Kniting\ProdKnitItemYarn;
use App\Traits\Eloquent\MsTraits;

class ProdKnitItemYarnImplementation implements ProdKnitItemYarnRepository
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
	public function __construct(ProdKnitItemYarn $model)
	{
		$this->model = $model;
	}
}
