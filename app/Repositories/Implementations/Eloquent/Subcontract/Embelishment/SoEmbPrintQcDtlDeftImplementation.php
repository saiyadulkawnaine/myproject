<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcDtlDeftRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintQcDtlDeft;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintQcDtlDeftImplementation implements SoEmbPrintQcDtlDeftRepository
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
	public function __construct(SoEmbPrintQcDtlDeft $model)
	{
		$this->model = $model;
	}
}
