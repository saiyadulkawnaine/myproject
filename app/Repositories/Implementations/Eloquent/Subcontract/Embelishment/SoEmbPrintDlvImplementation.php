<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintDlvRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintDlv;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintDlvImplementation implements SoEmbPrintDlvRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * ProdKnitImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoEmbPrintDlv $model)
	{
		$this->model = $model;
	}
}
