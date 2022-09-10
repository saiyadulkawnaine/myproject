<?php
namespace App\Repositories\Implementations\Eloquent\Commercial\LocalExport;
use App\Repositories\Contracts\Commercial\LocalExport\LocalExpInvoiceRepository;
use App\Model\Commercial\LocalExport\LocalExpInvoice;
use App\Traits\Eloquent\MsTraits; 
class LocalExpInvoiceImplementation implements LocalExpInvoiceRepository
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
	public function __construct(LocalExpInvoice $model)
	{
		$this->model = $model;
	}
	
	
}