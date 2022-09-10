<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceRepository;
use App\Model\Commercial\Export\ExpAdvInvoice;
use App\Traits\Eloquent\MsTraits; 
class ExpAdvInvoiceImplementation implements ExpAdvInvoiceRepository
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
	public function __construct(ExpAdvInvoice $model)
	{
		$this->model = $model;
	}
	
	
}