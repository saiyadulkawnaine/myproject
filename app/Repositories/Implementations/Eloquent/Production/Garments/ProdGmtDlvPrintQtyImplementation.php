<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintQtyRepository;
use App\Model\Production\Garments\ProdGmtDlvPrintQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvPrintQtyImplementation implements ProdGmtDlvPrintQtyRepository
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
	public function __construct(ProdGmtDlvPrintQty $model)
	{
		$this->model = $model;
	}
}
