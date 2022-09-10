<?php
namespace App\Repositories\Implementations\Eloquent\Workstudy;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupRepository;
use App\Model\Workstudy\WstudyLineSetup;
use App\Traits\Eloquent\MsTraits;

class WstudyLineSetupImplementation implements WstudyLineSetupRepository
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
	public function __construct(WstudyLineSetup $model)
	{
		$this->model = $model;
	}
}
