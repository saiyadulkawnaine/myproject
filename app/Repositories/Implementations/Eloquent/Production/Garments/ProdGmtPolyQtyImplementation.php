<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtPolyQtyRepository;
use App\Model\Production\Garments\ProdGmtPolyQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtPolyQtyImplementation implements ProdGmtPolyQtyRepository
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
	public function __construct(ProdGmtPolyQty $model)
	{
		$this->model = $model;
	}
}
