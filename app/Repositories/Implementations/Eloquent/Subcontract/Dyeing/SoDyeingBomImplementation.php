<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomRepository;
use App\Model\Subcontract\Dyeing\SoDyeingBom;
use App\Traits\Eloquent\MsTraits;
class SoDyeingBomImplementation implements SoDyeingBomRepository
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
	public function __construct(SoDyeingBom $model)
	{
		$this->model = $model;
	}
}
