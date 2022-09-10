<?php
namespace App\Repositories\Implementations\Eloquent\Production\Kniting;

use App\Repositories\Contracts\Production\Kniting\ProdKnitRcvByQcRepository;
use App\Model\Production\Kniting\ProdKnitRcvByQc;
use App\Traits\Eloquent\MsTraits;

class ProdKnitRcvByQcImplementation implements ProdKnitRcvByQcRepository
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
	public function __construct(ProdKnitRcvByQc $model)
	{
		$this->model = $model;
	}
}
