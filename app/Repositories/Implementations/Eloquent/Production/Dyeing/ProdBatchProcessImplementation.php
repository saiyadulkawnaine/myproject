<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchProcessRepository;
use App\Model\Production\Dyeing\ProdBatchProcess;
use App\Traits\Eloquent\MsTraits;

class ProdBatchProcessImplementation implements ProdBatchProcessRepository
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
	public function __construct(ProdBatchProcess $model)
	{
		$this->model = $model;
	}
}
