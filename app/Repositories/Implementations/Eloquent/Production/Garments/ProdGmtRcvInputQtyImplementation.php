<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtRcvInputQtyRepository;
use App\Model\Production\Garments\ProdGmtRcvInputQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtRcvInputQtyImplementation implements ProdGmtRcvInputQtyRepository
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
	public function __construct(ProdGmtRcvInputQty $model)
	{
		$this->model = $model;
	}
}
