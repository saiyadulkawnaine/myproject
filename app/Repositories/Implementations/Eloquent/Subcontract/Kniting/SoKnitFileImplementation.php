<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitFileRepository;
use App\Model\Subcontract\Kniting\SoKnitFile;
use App\Traits\Eloquent\MsTraits;
class SoKnitFileImplementation implements SoKnitFileRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitFileImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitFile $model)
	{
		$this->model = $model;
	}
}
