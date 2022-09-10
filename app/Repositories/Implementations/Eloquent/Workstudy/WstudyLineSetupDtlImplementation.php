<?php
namespace App\Repositories\Implementations\Eloquent\Workstudy;

use App\Repositories\Contracts\Workstudy\WstudyLineSetupDtlRepository;
use App\Model\Workstudy\WstudyLineSetupDtl;
use App\Traits\Eloquent\MsTraits;

class WstudyLineSetupDtlImplementation implements WstudyLineSetupDtlRepository
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
	public function __construct(WstudyLineSetupDtl $model)
	{
		$this->model = $model;
	}
}
