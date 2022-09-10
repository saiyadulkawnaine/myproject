<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRepository;
use App\Model\Production\Dyeing\ProdBatchFinishQc;
use App\Traits\Eloquent\MsTraits;

class ProdBatchFinishQcImplementation implements ProdBatchFinishQcRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(ProdBatchFinishQc $model)
	{
		$this->model = $model;
	}
}
