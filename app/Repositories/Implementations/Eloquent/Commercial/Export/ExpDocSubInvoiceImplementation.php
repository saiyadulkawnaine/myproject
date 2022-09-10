<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\Export;

use App\Repositories\Contracts\Commercial\Export\ExpDocSubInvoiceRepository;
use App\Model\Commercial\Export\ExpDocSubInvoice;
use App\Traits\Eloquent\MsTraits; 
class ExpDocSubInvoiceImplementation implements ExpDocSubInvoiceRepository
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
	public function __construct(ExpDocSubInvoice $model)
	{
		$this->model = $model;
	}
	
	
}