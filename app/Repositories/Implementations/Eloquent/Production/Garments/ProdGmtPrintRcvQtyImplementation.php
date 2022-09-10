<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvQtyRepository;
use App\Model\Production\Garments\ProdGmtPrintRcvQty;
use App\Traits\Eloquent\MsTraits;

class ProdGmtPrintRcvQtyImplementation implements ProdGmtPrintRcvQtyRepository
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
	public function __construct(ProdGmtPrintRcvQty $model)
	{
		$this->model = $model;
	}
}
