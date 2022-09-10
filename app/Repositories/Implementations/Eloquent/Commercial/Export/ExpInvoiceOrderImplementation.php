<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceOrderRepository;
use App\Model\Commercial\Export\ExpInvoiceOrder;
use App\Traits\Eloquent\MsTraits; 
class ExpInvoiceOrderImplementation implements ExpInvoiceOrderRepository
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
	public function __construct(ExpInvoiceOrder $model)
	{
		$this->model = $model;
	}
}