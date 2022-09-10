<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingQtyRepository;
use App\Model\Production\Garments\ProdGmtCuttingQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtCuttingQtyImplementation implements ProdGmtCuttingQtyRepository
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
	public function __construct(ProdGmtCuttingQty $model)
	{
		$this->model = $model;
	}
}
