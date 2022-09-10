<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceOrderRepository;
use App\Model\Commercial\LocalExport\LocalExpInvoiceOrder;
use App\Traits\Eloquent\MsTraits; 
class LocalExpInvoiceOrderImplementation implements LocalExpInvoiceOrderRepository
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
	public function __construct(LocalExpInvoiceOrder $model)
	{
		$this->model = $model;
	}
}