<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtIronQtyRepository;
use App\Model\Production\Garments\ProdGmtIronQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtIronQtyImplementation implements ProdGmtIronQtyRepository
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
	public function __construct(ProdGmtIronQty $model)
	{
		$this->model = $model;
	}
}
