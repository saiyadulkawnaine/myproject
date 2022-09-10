<?php
namespace App\Repositories\Implementations\Eloquent\Workstudy;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupMinAdjRepository;
use App\Model\Workstudy\WstudyLineSetupMinAdj;
use App\Traits\Eloquent\MsTraits;

class WstudyLineSetupMinAdjImplementation implements WstudyLineSetupMinAdjRepository
{
	use MsTraits;

	/**
	 * @var $model
	 */
	private $model;

	/**
	 * MsSysUserImplementation constructor.
	 *
	 * @param App\User $model
	 */
	public function __construct(WstudyLineSetupMinAdj $model)
	{
		$this->model = $model;
	}
}
