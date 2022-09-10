<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Embelishment;

use App\Repositories\Contracts\Subcontract\Embelishment\SoEmbPrintEntryRepository;
use App\Model\Subcontract\Embelishment\SoEmbPrintEntry;
use App\Traits\Eloquent\MsTraits;

class SoEmbPrintEntryImplementation implements SoEmbPrintEntryRepository
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
	public function __construct(SoEmbPrintEntry $model)
	{
		$this->model = $model;
	}
}
