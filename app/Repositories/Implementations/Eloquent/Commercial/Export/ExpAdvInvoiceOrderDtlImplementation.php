<?php

namespace App\Repositories\Implementations\Eloquent\Commercial\Export;
use App\Repositories\Contracts\Commercial\Export\ExpAdvInvoiceOrderDtlRepository;
use App\Model\Commercial\Export\ExpAdvInvoiceOrderDtl;
use App\Traits\Eloquent\MsTraits; 
class ExpAdvInvoiceOrderDtlImplementation implements ExpAdvInvoiceOrderDtlRepository
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
	public function __construct(ExpAdvInvoiceOrderDtl $model)
	{
		$this->model = $model;
	}
}