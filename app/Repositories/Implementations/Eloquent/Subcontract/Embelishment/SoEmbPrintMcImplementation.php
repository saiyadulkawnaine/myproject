<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintMcRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintMc;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintMcImplementation implements SoEmbPrintMcRepository
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
	public function __construct(SoEmbPrintMc $model)
	{
		$this->model = $model;
	}
}
