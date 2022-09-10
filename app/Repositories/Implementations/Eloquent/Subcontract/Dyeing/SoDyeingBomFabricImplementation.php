<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingBomFabricRepository;
use App\Model\Subcontract\Dyeing\SoDyeingBomFabric;
use App\Traits\Eloquent\MsTraits;
class SoDyeingBomFabricImplementation implements SoDyeingBomFabricRepository
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
	public function __construct(SoDyeingBomFabric $model)
	{
		$this->model = $model;
	}
}
