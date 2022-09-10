<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvInputQtyRepository;
use App\Model\Production\Garments\ProdGmtDlvInputQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvInputQtyImplementation implements ProdGmtDlvInputQtyRepository
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
	public function __construct(ProdGmtDlvInputQty $model)
	{
		$this->model = $model;
	}
}
