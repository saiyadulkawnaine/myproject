<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchRepository;
use App\Model\Production\Dyeing\ProdBatch;
use App\Traits\Eloquent\MsTraits;

class ProdBatchImplementation implements ProdBatchRepository
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
	public function __construct(ProdBatch $model)
	{
		$this->model = $model;
	}
}
