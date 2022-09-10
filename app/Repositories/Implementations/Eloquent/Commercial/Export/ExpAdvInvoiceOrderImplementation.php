<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceOrderRepository;
use App\Model\Commercial\Export\ExpAdvInvoiceOrder;
use App\Traits\Eloquent\MsTraits; 
class ExpAdvInvoiceOrderImplementation implements ExpAdvInvoiceOrderRepository
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
	public function __construct(ExpAdvInvoiceOrder $model)
	{
		$this->model = $model;
	}
}