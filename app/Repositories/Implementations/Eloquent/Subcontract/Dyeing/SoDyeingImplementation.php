<?php
namespace App\Repositories\Implementations\Eloquent\Subcontract\Dyeing;
use App\Repositories\Contracts\Subcontract\Dyeing\SoDyeingRepository;
use App\Model\Subcontract\Dyeing\SoDyeing;
use App\Traits\Eloquent\MsTraits;
class SoDyeingImplementation implements SoDyeingRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 *SoDyeingImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(SoDyeing $model)
	{
		$this->model = $model;
	}
}
