<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtCartonDetailQtyRepository;
use App\Model\Production\Garments\ProdGmtCartonDetailQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtCartonDetailQtyImplementation implements ProdGmtCartonDetailQtyRepository
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
	public function __construct(ProdGmtCartonDetailQty $model)
	{
		$this->model = $model;
	}
}
