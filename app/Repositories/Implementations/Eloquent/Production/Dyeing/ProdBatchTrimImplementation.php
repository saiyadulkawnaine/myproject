<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchTrimRepository;
use App\Model\Production\Dyeing\ProdBatchTrim;
use App\Traits\Eloquent\MsTraits;

class ProdBatchTrimImplementation implements ProdBatchTrimRepository
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
	public function __construct(ProdBatchTrim $model)
	{
		$this->model = $model;
	}
}
