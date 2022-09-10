<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintQc;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintQcImplementation implements SoEmbPrintQcRepository
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
	public function __construct(SoEmbPrintQc $model)
	{
		$this->model = $model;
	}
}
