<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishQcRollRepository;
use App\Model\Production\Dyeing\ProdBatchFinishQcRoll;
use App\Traits\Eloquent\MsTraits;

class ProdBatchFinishQcRollImplementation implements ProdBatchFinishQcRollRepository
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
	public function __construct(ProdBatchFinishQcRoll $model)
	{
		$this->model = $model;
	}
}
