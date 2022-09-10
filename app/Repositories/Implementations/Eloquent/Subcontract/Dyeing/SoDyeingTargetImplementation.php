<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingTargetRepository;
use App\Model\Subcontract\Dyeing\SoDyeingTarget;
use App\Traits\Eloquent\MsTraits;
class SoDyeingTargetImplementation implements SoDyeingTargetRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoDyeingTargetImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeingTarget $model)
	{
		$this->model = $model;
	}
}
