<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtDlvPrintOrderRepository;
use App\Model\Production\Garments\ProdGmtDlvPrintOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtDlvPrintOrderImplementation implements ProdGmtDlvPrintOrderRepository
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
	public function __construct(ProdGmtDlvPrintOrder $model)
	{
		$this->model = $model;
	}
}
