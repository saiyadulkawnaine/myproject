<?php

namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpInvoiceOrderDtlRepository;
use App\Model\Commercial\Export\ExpInvoiceOrderDtl;
use App\Traits\Eloquent\MsTraits; 
class ExpInvoiceOrderDtlImplementation implements ExpInvoiceOrderDtlRepository
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
	public function __construct(ExpInvoiceOrderDtl $model)
	{
		$this->model = $model;
	}
}