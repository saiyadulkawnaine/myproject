<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgRollRepository;
use App\Model\Production\Dyeing\ProdBatchFinishProgRoll;
use App\Traits\Eloquent\MsTraits;

class ProdBatchFinishProgRollImplementation implements ProdBatchFinishProgRollRepository
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
	public function __construct(ProdBatchFinishProgRoll $model)
	{
		$this->model = $model;
	}
}
