<?php
namespace App\Repositories\Implementations\Eloquent\Workstudy;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupLineRepository;
use App\Model\Workstudy\WstudyLineSetupLine;
use App\Traits\Eloquent\MsTraits;

class WstudyLineSetupLineImplementation implements WstudyLineSetupLineRepository
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
	public function __construct(WstudyLineSetupLine $model)
	{
		$this->model = $model;
	}
}
