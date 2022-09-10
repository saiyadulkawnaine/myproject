<?php

namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingPoRepository;
use App\Model\Subcontract\Dyeing\SoDyeingPo;
use App\Traits\Eloquent\MsTraits;
class SoDyeingPoImplementation implements SoDyeingPoRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * SoDyeingPoImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingPo $model)
	{
		$this->model = $model;
	}
}
