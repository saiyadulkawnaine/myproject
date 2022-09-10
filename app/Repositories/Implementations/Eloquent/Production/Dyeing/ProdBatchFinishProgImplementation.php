<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgRepository;
use App\Model\Production\Dyeing\ProdBatchFinishProg;
use App\Traits\Eloquent\MsTraits;

class ProdBatchFinishProgImplementation implements ProdBatchFinishProgRepository
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
	public function __construct(ProdBatchFinishProg $model)
	{
		$this->model = $model;
	}
}
