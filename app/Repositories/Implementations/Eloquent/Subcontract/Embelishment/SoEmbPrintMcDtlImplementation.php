<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcDtlRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintMcDtl;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintMcDtlImplementation implements SoEmbPrintMcDtlRepository
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
	public function __construct(SoEmbPrintMcDtl $model)
	{
		$this->model = $model;
	}
}
