<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRepository;
use App\Model\Subcontract\Dyeing\SoDyeingFabricRcv;
use App\Traits\Eloquent\MsTraits;
class SoDyeingFabricRcvImplementation implements SoDyeingFabricRcvRepository
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
	public function __construct(SoDyeingFabricRcv $model)
	{
		$this->model = $model;
	}
}
