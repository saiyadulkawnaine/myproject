<?php
namespace App\Repositories\Implementations\Eloquent\Production\Garments;

use App\Repositories\Contracts\Production\Garments\ProdGmtCuttingOrderRepository;
use App\Model\Production\Garments\ProdGmtCuttingOrder;
use App\Traits\Eloquent\MsTraits;

class ProdGmtCuttingOrderImplementation implements ProdGmtCuttingOrderRepository
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
	public function __construct(ProdGmtCuttingOrder $model)
	{
		$this->model = $model;
	}
}
