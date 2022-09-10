<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRcvRolRepository;
use App\Model\Subcontract\Dyeing\SoDyeingFabricRcvRol;
use App\Traits\Eloquent\MsTraits;
class SoDyeingFabricRcvRolImplementation implements SoDyeingFabricRcvRolRepository
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
	public function __construct(SoDyeingFabricRcvRol $model)
	{
		$this->model = $model;
	}
}
