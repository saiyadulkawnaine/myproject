<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlMinajRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintMcDtlMinaj;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintMcDtlMinajImplementation implements SoEmbPrintMcDtlMinajRepository
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
	public function __construct(SoEmbPrintMcDtlMinaj $model)
	{
		$this->model = $model;
	}
}
