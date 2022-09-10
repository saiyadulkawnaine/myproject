<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintQcDtlRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintQcDtl;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintQcDtlImplementation implements SoEmbPrintQcDtlRepository
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
	public function __construct(SoEmbPrintQcDtl $model)
	{
		$this->model = $model;
	}
}
