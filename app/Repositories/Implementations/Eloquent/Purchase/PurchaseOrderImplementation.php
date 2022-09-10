<?php

namespace App\Repositories\Implementations\Eloquent\Purchase;
use App\Repositories\Contracts\Purchase\PurchaseOrderRepository;
use App\Model\Purchase\PurchaseOrder;
use App\Traits\Eloquent\MsTraits;
class PurchaseOrderImplementation implements PurchaseOrderRepository
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
	public function __construct(PurchaseOrder $model)
	{
		$this->model = $model;
	}
}
