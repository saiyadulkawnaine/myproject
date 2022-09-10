<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;

use App\Repositories\Contracts\Commercial\LocalExport\LocalExpDocSubInvoiceRepository;
use App\Model\Commercial\LocalExport\LocalExpDocSubInvoice;
use App\Traits\Eloquent\MsTraits; 
class LocalExpDocSubInvoiceImplementation implements LocalExpDocSubInvoiceRepository
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
	public function __construct(LocalExpDocSubInvoice $model)
	{
		$this->model = $model;
	}
	
	
}