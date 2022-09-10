<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlOrdRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintMcDtlOrd;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintMcDtlOrdImplementation implements SoEmbPrintMcDtlOrdRepository
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
	public function __construct(SoEmbPrintMcDtlOrd $model)
	{
		$this->model = $model;
	}
}
