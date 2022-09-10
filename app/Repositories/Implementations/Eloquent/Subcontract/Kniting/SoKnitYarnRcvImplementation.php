<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Kniting;
use App\Repositories\Contracts\Subcontract\Kniting\SoKnitYarnRcvRepository;
use App\Model\Subcontract\Kniting\SoKnitYarnRcv;
use App\Traits\Eloquent\MsTraits;
class SoKnitYarnRcvImplementation implements SoKnitYarnRcvRepository
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
	public function __construct(SoKnitYarnRcv $model)
	{
		$this->model = $model;
	}
}
