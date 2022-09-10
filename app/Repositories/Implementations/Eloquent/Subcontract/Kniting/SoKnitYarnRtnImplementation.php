<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRtnRepository;
use App\Model\Subcontract\Kniting\SoKnitYarnRtn;
use App\Traits\Eloquent\MsTraits;
class SoKnitYarnRtnImplementation implements SoKnitYarnRtnRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoKnitRefImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoKnitYarnRtn $model)
	{
		$this->model = $model;
	}
}
