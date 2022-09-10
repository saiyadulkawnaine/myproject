<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtPrintRcvOrderRepository;
use App\Model\Production\Garments\ProdGmtPrintRcvOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtPrintRcvOrderImplementation implements ProdGmtPrintRcvOrderRepository
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
	public function __construct(ProdGmtPrintRcvOrder $model)
	{
		$this->model = $model;
	}
}
