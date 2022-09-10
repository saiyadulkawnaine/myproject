<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchRollRepository;
use App\Model\Production\Dyeing\ProdBatchRoll;
use App\Traits\Eloquent\MsTraits;

class ProdBatchRollImplementation implements ProdBatchRollRepository
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
	public function __construct(ProdBatchRoll $model)
	{
		$this->model = $model;
	}
}
