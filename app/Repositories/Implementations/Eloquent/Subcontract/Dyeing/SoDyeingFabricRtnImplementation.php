<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingFabricRtnRepository;
use App\Model\Subcontract\Dyeing\SoDyeingFabricRtn;
use App\Traits\Eloquent\MsTraits;
class SoDyeingFabricRtnImplementation implements SoDyeingFabricRtnRepository
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
	public function __construct(SoDyeingFabricRtn $model)
	{
		$this->model = $model;
	}
}
