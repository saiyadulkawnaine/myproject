<?php
namespace App\Repositories\Implementations\Eloquent\Production\Dyeing;

use App\Repositories\Contracts\Production\Dyeing\ProdBatchFinishProgChemRepository;
use App\Model\Production\Dyeing\ProdBatchFinishProgChem;
use App\Traits\Eloquent\MsTraits;

class ProdBatchFinishProgChemImplementation implements ProdBatchFinishProgChemRepository
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
	public function __construct(ProdBatchFinishProgChem $model)
	{
		$this->model = $model;
	}
}
